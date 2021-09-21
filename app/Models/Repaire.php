<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Repaire extends Model
{
    use HasFactory;
    protected $table = 'tb_ship_repaire';
    
    public function getList($params) {
        $selector = self::where('ship_id', $params['ship_id']);

        if(isset($params['status']) && $params['status'] != REPAIRE_STATUS_ALL) {
            if($params['status'] == REPAIRE_STATUS_UNCOMPLETE) 
                $selector->whereNull('completed_at');
            else {
                $selector->whereNotNull('completed_at');
            }
        }

        $year = date('Y');
        $month = '01';
        if(isset($params['year']))
            $year = $params['year'];

        if(isset($params['month']))
            $month = sprintf('%02d', $params['month']);

        $date = $year . '-' . $month;
        $selector->whereRaw(DB::raw('mid(created_at, 1, 7) like "' . $date . '"'));

        $records = $selector->get();

        return $records;
    }

    public function udpateData($params) {
        $ids = $params['id'];
        $ship_id = $params['ship_id'];

        try {
            DB::beginTransaction();

            foreach($ids as $key => $item) {
                if(isset($item) && $item != 0) {
                    $tbl = self::find($item);
                } else {
                    $tbl = new self();
                }
    
                $tbl['ship_id'] = $ship_id;
                $tbl['serial_no'] = isset($params['serial_no'][$key]) ? $params['serial_no'][$key] : '';
                $tbl['request_date'] = isset($params['request_date'][$key]) ? $params['request_date'][$key] : null;
                $tbl['department'] = isset($params['department'][$key]) ? $params['department'][$key] : 0;
                $tbl['charge'] = isset($params['charge'][$key]) ? $params['charge'][$key] : 0;
                $tbl['type'] = isset($params['type'][$key]) ? $params['type'][$key] : 0;
                $tbl['job_description'] = isset($params['job_description'][$key]) ? $params['job_description'][$key] : '';
                $tbl['completed_at'] = isset($params['completed_at'][$key]) ? $params['completed_at'][$key] : null;
                $tbl['remark'] = isset($params['remark'][$key]) ? $params['remark'][$key] : '';
                $tbl->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }

        return true;
    }
}
