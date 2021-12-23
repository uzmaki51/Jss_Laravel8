<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShipManage\ShipRegister;
use Litipk\BigNumbers\Decimal;
use App\Models\Convert\VoyLog;
use DB;

class Voy extends Model
{
    use HasFactory;

    protected $table = 'tbl_voy_log';

    public function getVoyInfoByYear($shipId, $year) {
        $shipId = $shipId;
        $year = $year;

        $shipInfo = ShipRegister::where('IMO_No', $shipId)->first();

        if($shipInfo == null) return -99;

        $filterYear = substr($year, -2);
        $voyList = self::where('Ship_ID', $shipId)->whereRaw(DB::raw('mid(CP_ID, 1, 2) like ' . $filterYear))->orderBy('CP_ID', 'asc')->groupBy('CP_ID')->select('CP_ID')->get();

        if($voyList == null || count($voyList) == 0) return 0;

        $total_sail_time = Decimal::create(0);
        $total_count = Decimal::create(0);
        foreach($voyList as $key => $item) {
            $voyInfo = $this->getVoyInfoByCP($shipId, $item->CP_ID);

            $_total_time = $voyInfo['total_time'];
            $_total_count = $voyInfo['voy_count'];

            $total_sail_time = $total_sail_time->add(Decimal::create($_total_time));
            $total_count = $total_count->add(Decimal::create($_total_count));
        }

        return array(
            'total_time'        => round($total_sail_time->__toString(), 2),
            'total_count'       => round($total_count->__toString(), 2),
        );
    }

    public function getVoyInfoByCP($shipId, $voyId) {

        $voyLogTbl = new VoyLog();
        $beforInfo = $voyLogTbl->getBeforeInfo($shipId, $voyId);

        $currentTbl = self::where('Ship_ID', $shipId)
            ->where('CP_ID', $voyId)
            ->orderBy('Voy_Date', 'asc')
            ->orderBy('Voy_Hour', 'asc')
            ->orderBy('Voy_Minute', 'asc')
            ->orderBy('GMT', 'asc')
            ->orderBy('id', 'asc');

        if($beforInfo == []) {
            $tmp = $currentTbl;
            $beforInfo = $tmp->first();
            if(!isset($beforInfo))
                return false;
        }

        $currentInfo = $voyLogTbl->getCurrentData($shipId, $voyId);

        if($currentInfo == []) return false;

        $_sailTime = 0;
        $_loadTime = 0;
        $_dischTime = 0;
        $_waitTime = 0;
        $_weatherTime = 0;
        $_repairTime = 0;
        $_supplyTime = 0;
        $_elseTime = 0;
        $_otherTime = 0;

        $start_date = $beforInfo->Voy_Date . ' ' . $beforInfo->Voy_Hour . ':' . $beforInfo->Voy_Minute . ':00';
        $start_gmt = $beforInfo->GMT;
        $total_distance = 0;

        foreach($currentInfo as $key => $item) {
            if($item->Voy_Type == DYNAMIC_SUB_SALING) {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_sailTime += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            } else if($item->Voy_Type == DYNAMIC_SUB_LOADING) {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_loadTime += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            } else if($item->Voy_Type == DYNAMIC_SUB_DISCH) {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_dischTime += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            } else if($item->Voy_Type == DYNAMIC_SUB_WAITING) {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_waitTime += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            } else if($item->Voy_Type == DYNAMIC_SUB_WEATHER) {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_weatherTime  += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            } else if($item->Voy_Type == DYNAMIC_SUB_REPAIR) {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_repairTime += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            } else if($item->Voy_Type == DYNAMIC_SUB_SUPPLY) {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_supplyTime += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            } else if($item->Voy_Type == DYNAMIC_SUB_ELSE) {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_elseTime += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            } else {
                $end_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
                $_otherTime += Common::getTermDay($start_date, $end_date, $start_gmt, $item->GMT);
            }

            $start_date = $item->Voy_Date . ' ' . $item->Voy_Hour . ':' . $item->Voy_Minute . ':00';
            $start_gmt = $item->GMT;

            $total_distance += $item->Sail_Distance;
        }

        $mainInfo['sail_time'] = round($_sailTime, 2);
        $mainInfo['load_time'] = round($_loadTime, 2);
        $mainInfo['disch_time'] = round($_dischTime, 2);
        $mainInfo['wait_time'] = round($_waitTime + $_weatherTime + $_repairTime + $_supplyTime + $_elseTime, 2);

        $total_time = round($_sailTime, 2) + round($_loadTime, 2) + round($_dischTime, 2) + round($_waitTime, 2) + round($_weatherTime, 2) + round($_repairTime, 2) + round($_supplyTime, 2) + round($_elseTime, 2) + round($_otherTime, 2);
        $total_time = round($total_time, 2);

        return array(
            'total_time'        => $total_time,
            'start_date'        => $beforInfo->Voy_Date,
            'end_date'          => $currentInfo[count($currentInfo) - 1]->Voy_Date,
            'voy_count'         => count($currentInfo)
        );

    }
}
