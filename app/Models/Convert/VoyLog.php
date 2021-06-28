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

class VoyLog extends Model
{
    protected $table = "tbl_voy_log";
    public $timestamps = false;

    public function getYearList($shipId) {
        $yearList = [];
        $shipInfo = self::where('Ship_ID', $shipId)->orderBy('Voy_Date', 'asc')->first();
        if($shipInfo == null) {
            $baseYear = date('Y');
        } else {
            $baseYear = substr($shipInfo->Voy_Date, 0, 4);
        }

        for($year = date('Y'); $year >= $baseYear; $year --) {
            $yearList[] = $year;
        }

        return $yearList;
    }

    public function getVoyRecord($shipId = 0, $voyId = 0, $last = false, $all = true) {
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
        $selector = self::where('Ship_ID', $shipId)
                    ->where('CP_ID', '<', $voyId)
                    ->where('Voy_Type', DYNAMIC_CMPLT_DISCH)
                    ->orderBy('CP_ID', 'desc')
                    ->first();

        if($selector == null) 
            return [];
        
        return $selector;
    }

    public function getLastInfo($shipId, $voyId) {
        $selector = self::where('Ship_ID', $shipId)
                    ->where('CP_ID', $voyId)
                    ->where('Voy_Type', DYNAMIC_CMPLT_DISCH)
                    ->first();

        if($selector == null)
            return [];
        
        return $selector;        
    }
}
