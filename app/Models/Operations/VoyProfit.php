<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-09-16
 * Time: 오후 7:57
 */

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VoyProfit extends Model
{
    protected $table = "tbl_voy_profit";
    public $timestamps = false;

    public static function makeVoyId() {
        $list = VoyProfit::all();
        foreach ($list as $profit){
            $cp = CP::where('CP_No', $profit['VOY'])->first();

            $profit['VoyId'] = $cp['id'];
            $profit->save();
        }
    }
}