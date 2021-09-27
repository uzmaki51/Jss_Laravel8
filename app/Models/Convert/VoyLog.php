<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/10/19
 * Time: 10:16
 */

namespace App\Models\Convert;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Operations\CP;
use App\Models\ShipTechnique\ShipPort;

class VoyLog extends Model
{
    protected $table = "tbl_voy_log";
    protected $table_ship = "tb_ship_register";

    public $timestamps = false;

    public function getYearList($shipId) {
        $yearList = [];
        $shipInfo = DB::table($this->table_ship)->where('IMO_No', $shipId)->first();
        if($shipInfo == null) {
            $baseYear = date('Y');
        } else {
            $baseYear = substr($shipInfo->RegDate, 0, 4);
        }

        for($year = date('Y'); $year >= $baseYear; $year --) {
            $yearList[] = $year;
        }

        return $yearList;
    }

    public function getCurrentData($shipId = 0, $voyId = 0, $last = false, $all = true) {
        if($shipId == 0 || $voyId == 0) return [];

        if($last)
            $orderBy = 'desc';
        else
            $orderBy = 'asc';

        $selector = self::where('Ship_ID', $shipId)
            ->where('CP_ID', $voyId)
            ->orderBy('Voy_Date', $orderBy)
            ->orderBy('Voy_Hour', $orderBy)
            ->orderBy('Voy_Minute', $orderBy)
            ->orderBy('GMT', $orderBy);

        if($all) {
            $result = $selector->get();
            if(!isset($result) || count($result) == 0)
                $result = [];
        } else {
            $result = $selector->first();
            if($result == null)
                $result = [];
        }

        return $result;
    }

    public function getBeforeInfo($shipId, $voyId) {
        // Get last record of voy before this voy.
        // Voy Status == 19(DYNAMIC_VOYAGE)
        $record = self::where('Ship_ID', $shipId)
            ->where('CP_ID', '<', $voyId)
            ->where('Voy_Status', DYNAMIC_VOYAGE)
            ->orderBy('CP_ID', 'desc')
            ->orderBy('Voy_Date', 'desc')
            ->orderBy('Voy_Hour', 'desc')
            ->orderBy('Voy_Minute', 'desc')
            ->orderBy('GMT', 'desc')
            ->first();

        if($record == null)
            $record = self::where('Ship_ID', $shipId)
                ->where('CP_ID', '<', $voyId)
                ->where('Voy_Status', DYNAMIC_CMPLT_DISCH)
                // ->where('Cargo_Qtty', 0)
                ->orderBy('CP_ID', 'desc')
                ->orderBy('Voy_Date', 'desc')
                ->orderBy('Voy_Hour', 'desc')
                ->orderBy('Voy_Minute', 'desc')
                ->orderBy('GMT', 'desc')
                ->first();

        else return $record;

        if($record == null) {
            $record = self::where('Ship_ID', $shipId)
                ->where('CP_ID', $voyId)
                // ->orderBy('id', 'asc')
                ->orderBy('Voy_Date', 'asc')
                ->orderBy('Voy_Hour', 'asc')
                ->orderBy('Voy_Minute', 'asc')
                ->orderBy('GMT', 'asc')
                ->first();

            if($record == null) return [];
        }
        
        return $record;
    }

    public function getLastInfo($shipId, $voyId) {
        $record = self::where('Ship_ID', $shipId)
            ->where('CP_ID', $voyId)
            ->where('Voy_Status', DYNAMIC_VOYAGE)
            ->orderByDesc('Voy_Date')
            ->orderByDesc('Voy_Hour')
            ->orderByDesc('Voy_Minute')
            ->orderByDesc('GMT')
            ->orderByDesc('id')
            ->first();

        if($record != null) return $record;

        $record = self::where('Ship_ID', $shipId)
                ->where('CP_ID', $voyId)
                ->where('Voy_Status', DYNAMIC_CMPLT_DISCH)
                ->where('Cargo_Qtty', 0)
                ->orderByDesc('Voy_Date')
                ->orderByDesc('Voy_Hour')
                ->orderByDesc('Voy_Minute')
                ->orderByDesc('GMT')
                ->first();

        if($record == null)
            return [];
        
        return $record;
    }

    public function getVoyList($params) {
        if(!isset($params['shipId'])) return [];
        $shipId = $params['shipId'];

        if(!isset($params['voyId'])) return [];
        $voyId = $params['voyId'];

        if(!isset($params['year']))
            $date = date("Y");
        else
            $date = $params['year'];

        $retVal = [];
        if(!isset($params['type']) || $params['type'] == 'all') {
            // 1. Get last record before this voy.
            $before = $this->getBeforeInfo($shipId, $voyId);
            // 2. Get last record of this voy. 
            $last = $this->getLastInfo($shipId, $voyId);
            // 3. Get current voy data.
            $current = $this->getCurrentData($shipId, $voyId);

            if($before != [])
                $min_date = $before;
            else
                $min_date = EMPTY_DATE;

            if($last != [])
                $max_date = $last;
            else
                $max_date = EMPTY_DATE;

            $retVal['min_date'] = $min_date;
            $retVal['max_date'] = $max_date;
            $retVal['prevData'] = $before;
            $retVal['currentData'] = $current;
        } else {
            // Get analyzed data group by Year

            // Get suffix of year (Ex. 2021 => 21)
            $year = substr($date, 2, 2);
            $records = self::where('Ship_ID', $shipId)
                ->whereRaw(DB::raw('mid(CP_ID, 1, 2) like ' . $year))
                ->orderBy('Voy_Date', 'asc')
                ->orderBy('Voy_Hour', 'asc')
                ->orderBy('Voy_Minute', 'asc')
                ->orderBy('GMT', 'asc')
                ->orderBy('id', 'asc')
                ->groupBy('CP_ID')
                ->select('CP_ID');

            $records = $records->get();
            $voyData = [];
            $cpData = [];
            foreach($records as $key => $item) {
                $voy_id = $item->CP_ID;
                $before = $this->getBeforeInfo($shipId, $voy_id);
                $voyData[$voy_id][] = $before;
                $currentData = $this->getCurrentData($shipId, $voy_id);
                foreach($currentData as $cur_key => $cur_item)
                    $voyData[$voy_id][] = $cur_item;

                $cpInfo = CP::where('Ship_ID', $shipId)->where('Voy_No', $item->CP_ID)->first();
                $portTbl = new ShipPort();
                if(!isset($cpInfo)) {
                    $retVal['cpData'][$item->CP_ID] = [];
                    $cpInfo->LPort = '';
                    $cpInfo->DPort = '';
                } else {
                    $cpInfo->LPort = $portTbl->getPortNames($cpInfo->LPort, false);
                    $cpInfo->DPort = $portTbl->getPortNames($cpInfo->DPort, false);
                    $retVal['cpData'][$item->CP_ID] = $cpInfo;
                    
                }

                $retVal['voyData'][] = $voy_id;
            }

            $retVal['currentData'] = $voyData;
        }

        return $retVal;
    }
}
