<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/25
 * Time: 9:47
 */

namespace App\Models\ShipMember;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipMemberEvaluation extends Model
{
    protected $table = 'tb_ship_member_evaluation';

    protected $fillable = ['value1','value2','value3','value4','value5','value6','value7','value8','value9','value10','master','ce','co','1e','operational','general_dept','technical_dept','sign1','sign2','sign3','sign4','qualified'];
}
