<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/5/14
 * Time: 7:41
 */

namespace App\Models\ShipManage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipManage\ShipCertList;

class ShipMaterialRegistry extends Model
{
    protected $table = 'tb_ship_material_registry';
}