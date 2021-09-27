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

use App\Http\Controllers\Controller;
use App\Models\Member\Post;
use App\Models\Operations\Cargo;
use App\Models\Operations\CP;
use App\Models\ShipMember\ShipPosition;
use App\Models\ShipTechnique\ShipPort;
use App\Models\Finance\ExpectedCosts;
use App\Models\Finance\ReportSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Util;
use App\Models\Menu;
use App\Models\UserInfo;
use App\Models\Member\Unit;
use App\Models\Decision\DecisionReport;

use App\Models\Plan\MainPlan;
use App\Models\Plan\SubPlan;
use App\Models\Plan\ReportPerson;
use App\Models\Plan\ReportPersonWeek;
use App\Models\Plan\ReportPersonMonth;
use App\Models\Plan\UnitWeekReport;
use App\Models\Plan\UnitMonthReport;

use App\Models\Board\News;
use App\Models\Board\NewsTema;
use App\Models\Board\NewsResponse;
use App\Models\Board\NewsRecommend;
use App\Models\Board\NewsHistory;

use App\Models\Attend\AttendUser;
use App\Models\Attend\AttendType;
use App\Models\Attend\AttendTime;
use App\Models\Attend\AttendRest;
use App\Models\Attend\AttendShip;

use App\Models\ShipManage\Ship;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\ShipMember;
use App\Models\Convert\VoyLog;
use Carbon\Carbon;


use Auth;

class VoySettleMain extends Model
{
    protected $table = "tbl_voy_settle_main";
    protected $_DAY_UNIT = 1000 * 3600;
}
