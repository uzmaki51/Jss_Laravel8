<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;

use App\Models\Operations\Account;
use App\Models\Operations\AcItem;
use App\Models\Operations\PayMode;
use App\Models\Operations\VoyProfit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Util;

use App\Models\Menu;
use App\Models\BreadCrumb;
use App\Models\ShipManage\ShipRegister;
use App\Models\Decision\DecisionReport;

use App\Models\Operations\AcItemDetail;
use App\Models\Operations\YearlyPlanInput;
use App\Models\Operations\VoyStatus;
use App\Models\Operations\VoyStatusEvent;
use App\Models\Operations\VoyStatusType;
use App\Models\Operations\YearlyQuarterMonthPlan;
use App\Models\Operations\CP;
use App\Models\Operations\StandardCp;
use App\Models\Operations\VoyLog;
use App\Models\Operations\Invoice;
use App\Models\Operations\YearlyPlan;
use App\Models\Operations\ShipOilSupply;
use App\Models\ShipTechnique\ShipPort;
use App\Models\Operations\Cargo;
use App\Models\Operations\VoyProRandom;
use App\Models\Operations\VoyProgramPractice;
use App\Models\Operations\SailDistance;
use Config;

use Illuminate\Database\Eloquent;

use Auth;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('operation/operationPlan');
    }


    public function incomeExpense(Request $request) {
        $url = $request->path();
        $breadCrumb = BreadCrumb::getBreadCrumb($url);

        $start_year = DecisionReport::select(DB::raw('MIN(report_date) as min_date'))->first();
        if(empty($start_year)) {
            $start_year = '2020-01-01';
        } else {
            $start_year = substr($start_year['min_date'],0,4);
        }
        $user_pos = Auth::user()->pos;
        if($user_pos == STAFF_LEVEL_SHAREHOLDER || $user_pos == STAFF_LEVEL_CAPTAIN)
            $shipList = ShipRegister::getShipForHolderWithDelete();
        else {
            $shipList = ShipRegister::orderBy('id')->get();
        }
        return view('operation.incomeExpense', array(
            'start_year' => $start_year,
            'shipList'   => $shipList,
            'breadCrumb'    => $breadCrumb
        ));
    }

    public function incomeAllExpense(Request $request) {
        $url = $request->path();
        $breadCrumb = BreadCrumb::getBreadCrumb($url);
        
        $start_year = DecisionReport::select(DB::raw('MIN(report_date) as min_date'))->first();
        if(empty($start_year)) {
            $start_year = '2020-01-01';
        } else {
            $start_year = substr($start_year['min_date'],0,4);
        }
        $user_pos = Auth::user()->pos;
        if($user_pos == STAFF_LEVEL_SHAREHOLDER || $user_pos == STAFF_LEVEL_CAPTAIN)
            $shipList = ShipRegister::getShipForHolderWithDelete();
        else {
            $shipList = ShipRegister::orderBy('id')->get();
        }
        return view('operation.incomeAllExpense', array(
            'start_year' => $start_year,
            'shipList'   => $shipList,
            'breadCrumb'    => $breadCrumb
        ));
    }

    public function ajaxIncomeExportListByShipForPast(Request $request) {
        $params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getIncomeExportListForPast($params);

		return response()->json($reportList);
    }

    public function ajaxIncomeExportListByShip(Request $request) {
        $params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getIncomeExportList($params);

		return response()->json($reportList);
    }

    public function ajaxListBySOA(Request $request) {
        $params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getListBySOA($params);

		return response()->json($reportList);
    }

    public function ajaxListByAll(Request $request) {
        $params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getListByAll($params);

		return response()->json($reportList);
    }

    

}