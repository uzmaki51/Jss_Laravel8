<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/13
 * Time: 9:39
 */

namespace App\Http\Controllers\Decision;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;

use App\Models\Convert\VoyLog;
use App\Models\Operations\CP;
use App\Models\Decision\DecEnvironment;
use App\Models\Decision\DecisionFlow;
use App\Models\Decision\DecisionReport;
use App\Models\Decision\DecisionNote;
use App\Models\Decision\Decider;
use App\Models\Decision\DecisionReportAttachment;
use App\Models\Decision\ReadReport;

use App\Models\Operations\AcItem;
use App\Models\ShipManage\ShipRegister;
use App\Models\UserInfo;
use App\Models\User;
use App\Models\Common;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Member\Unit;
use App\Models\Finance\AccountPersonalInfo;
use App\Models\ShipManage\Ctm;

use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class DecisionController extends Controller
{
	protected $userinfo;

	public function __construct() {
		$this->middleware('auth');
	}

	// Report List
	public function receivedReport(Request $request) {
		$shipList = ShipRegister::all();
		$params = $request->all();

		$id = -1;
		if(isset($params['id']))
			$id = $params['id'];

		$tbl = new DecisionReport();
		$yearList = $tbl->getYearList($id);
		$lastId = $tbl->getLastId();

		return view('decision.received_report', [
				'draftId'  	=> $id, 
				'shipList'  => $shipList,
				'years'		=> $yearList,

				'lastId'		=> $lastId
			]);
	}

	// Draft List
	public function draftReport(Request $request) {
		$shipList = ShipRegister::all();
		$params = $request->all();

		$id = -1;
		if(isset($params['id']))
			$id = $params['id'];

		$tbl = new DecisionReport();
		$yearList = $tbl->getYearList($id);

		return view('decision.draft_report', [
			'draftId'  	=> $id, 
			'shipList'  => $shipList,
			'years'		=> $yearList,
		]);
	}

	public function redirect(Request $request) {
		$params = $request->all();

		$draftId = $params['id'];

		return redirect('decision/receivedReport?id=' . $draftId);
	}

	// Added by Uzmaki
	public function reportSubmit(Request $request) {
		$params = $request->all();

		$reportId = $params['reportId'];
		if(isset($reportId) && $reportId != "")
			$reportTbl = DecisionReport::find($reportId);
		else
			$reportTbl =new DecisionReport();

		$draftId = $params['draftId'];

		$user = Auth::user();
		$commonTbl = new Common();
		if($params['reportType'] != REPORT_STATUS_DRAFT) {
			if((!isset($reportId) || $reportId == "") || $draftId != -1) {
				$reportNo = $commonTbl->generateReportID($params['report_date']);
				if($reportNo == false) return redirect()->back();
				$reportTbl['report_id'] = $reportNo;
				
			} else if(isset($reportId) && $reportId != '') {
				$reportNo = DecisionReport::where('id', $reportId)->where('state', REPORT_STATUS_REQUEST)->first();
				if($reportNo == null) {
					$reportNo = $commonTbl->generateReportID($params['report_date']);
					$reportTbl['report_id'] = $reportNo;
				} else {
					$insertId = $reportNo;
					$reportNo = substr($reportNo->report_id, 0, 2);
					// if($params['report_date'])
					$reportDate = date('y', strtotime($params['report_date']));
					if($reportNo == $reportDate) {
						$reportTbl['report_id'] = $insertId->report_id;
					} else {
						$reportTbl['report_id'] = $commonTbl->generateReportID($params['report_date']);
					}

				}

			}
		} else {
			$reportTbl['report_id'] = 0;
		}
		$reportTbl['obj_type'] = $params['object_type'];

		if(!isset($params['report_date']) || $params['report_date'] == EMPTY_DATE) {
			$reportTbl['report_date'] = null;	
		} else
			$reportTbl['report_date'] = $params['report_date'];

		$reportTbl['flowid'] = $params['flowid'];
		if($params['object_type'] == OBJECT_TYPE_SHIP) {
			$reportTbl['shipNo'] = isset($params['shipNo']) ? $params['shipNo'] : null;
			$reportTbl['voyNo'] = isset($params['voyNo']) ? $params['voyNo'] : null;
			$reportTbl['obj_no'] = null;
			$reportTbl['obj_name'] = null;
		} else if($params['object_type'] == OBJECT_TYPE_PERSON) {
			$reportTbl['obj_no'] = isset($params['obj_no']) ? $params['obj_no'] : null;
			if(isset($params['obj_no']))
				$reportTbl['obj_name'] = AccountPersonalInfo::where('id', $params['obj_no'])->first()->person;
			else
				$reportTbl['obj_name'] = '';

			$reportTbl['shipNo'] = null;
			$reportTbl['voyNo'] = null;
		}

		if($params['flowid'] == REPORT_TYPE_CONTRACT) {
			$reportTbl['profit_type'] = null;
			$reportTbl['amount'] = null;
			$reportTbl['currency'] = null;
		} else {
			$reportTbl['profit_type'] = isset($params['profit_type']) ? $params['profit_type'] : null;
			$reportTbl['amount'] = isset($params['amount']) ? _convertStr2Int($params['amount']) : 0;
			$reportTbl['currency'] = isset($params['currency']) ? $params['currency'] : CNY_LABEL;
		}

		$reportTbl['depart_id'] = $params['depart_id'];
		$reportTbl['creator'] = $user->id;
		$reportTbl['content'] = isset($params['content']) ? $params['content'] : '';
		$reportTbl['state'] = $params['reportType'];
		$reportTbl['isvisible'] = 0;
		$reportTbl->save();

		$isRegister = false;
		if(isset($reportId) && $reportId != "") {
			$lastId = $reportId;
			$isRegister = false;
		} else {
			$last = DecisionReport::where('state', STATUS_ACTIVE)->orderBy('id', 'desc')->first();
			if($last == null) return redirect()->back();
			$lastId = $last['id'];
			$isRegister = true;
		}

		$attachmentTbl = new DecisionReportAttachment();
		if ($params['file_remove'] == '1') {
			$attachmentTbl->deleteRecord($lastId);
            $reportTbl['attachment'] = 0;
        } else if($request->hasFile('attachment')) {
			$reportTbl['attachment'] = 1;
			$file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
			$name = date('Ymd_His') . '_' . Str::random(10). '.' . $file->getClientOriginalExtension();
			$file->move(public_path() . '/reports/' . $params['flowid'] . '/', $name);
			$fileDir =  public_path() . '/reports/' . $params['flowid'] . '/' . $name;
			$fileLink = url('/') . '/reports/' . $params['flowid'] . '/' . $name;
			$ret = $attachmentTbl->updateAttach($lastId, $fileName, $fileDir, $fileLink);
		}
		
		$reportTbl->save();
		Session::put('last_session', true);

		if($params['reportType'] != REPORT_STATUS_DRAFT)
			return redirect('decision/receivedReport');
		else
			return redirect('decision/draftReport');
	}
	public function getACList(Request $request) {
		$param = $request->all();
		if(!isset($param['type']) || $param['type'] == "")
			return response()->json(array());

		$type = $param['type'];
		$ACList = AcItem::where('C_D', g_enum('ReportTypeData')[$type])->get();
		return response()->json($ACList);
	}

	public function ajaxGetReceive(Request $request) {
		$params = $request->all();
		$userid = Auth::user()->id;

		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForDatatable($params);

		return response()->json($reportList);
	}

	public function ajaxGetDraft(Request $request) {
		$params = $request->all();
		$userid = Auth::user()->id;

		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForDatatable($params, REPORT_STATUS_DRAFT);

		return response()->json($reportList);
	}

	public function ajaxReportDecide(Request $request) {
		$params = $request->all();
		$userid = Auth::user()->id;
		$userRole = Auth::user()->isAdmin;

		if($userRole != SUPER_ADMIN)
			return response()->json('-1');

		$decideTbl = new DecisionReport();
		$ret = $decideTbl->decideReport($params);


		return response()->json($ret);
	}

	public function ajaxReportDetail(Request $request) {
		$params = $request->all();
		$userid = Auth::user()->id;
		$userRole = Auth::user()->isAdmin;

		Session::forget('reportFiles');

		$decideTbl = new DecisionReport();
		$retVal = $decideTbl->getReportDetail($params);

		return response()->json($retVal);
	}

	public function ajaxReportData(Request $request) {
		$params = $request->all();

		$shipList = ShipRegister::all();
		foreach($shipList as $key => $item) {
			$shipList[$key]->NickName = $shipList[$key]->NickName == '' ? $shipList[$key]->shipName_En : $shipList[$key]->NickName;
		}

		if(isset($params['shipId'])) {
			$voyList = CP::where('Ship_ID', $params['shipId'])->orderBy('Voy_No', 'desc')->groupBy('Voy_No')->get();
		} else {
			$voyList = array();
		}

		$_lastSession = Session::get('last_session');
		if($_lastSession == null)
			$beforeReport = false;
		else
			$beforeReport = true;

		return response()->json(array('shipList'    => $shipList, 'voyList' => $voyList));
	}

	public function ajaxProfitList(Request $request) {
		$params = $request->all();

		if(isset($params['profitType']))
			$profitType = $params['profitType'];
		else
			$profitType = 0;

		$profitList = ACItem::where('C_D', $profitType)->orderBy('id')->get();

		return response()->json($profitList);

	}

	public function ajaxReportFile(Request $request) {
		$params = $request->all();

		if(isset($params['id']))
			$id = $params['id'];
		else
			$id = 0;
		
		$lastId = $id;
		$reportTbl = DecisionReport::find($lastId);
		$attachmentTbl = new DecisionReportAttachment();
		$hasFile = $request->file('file');
		if(isset($hasFile)) {
			$reportTbl['attachment'] = 1;
			$file = $request->file('file');
            $fileName = $file->getClientOriginalName();
			$name = date('Ymd_His') . '_' . Str::random(10). '.' . $file->getClientOriginalExtension();
			$file->move(public_path() . '/reports/' . $params['flowid'] . '/', $name);
			$fileDir =  public_path() . '/reports/' . $params['flowid'] . '/' . $name;
			$fileLink = url('/') . '/reports/' . $params['flowid'] . '/' . $name;
			$ret = $attachmentTbl->updateAttach($lastId, $fileName, $fileDir, $fileLink);

			$reportTbl->save();
		}

		return response()->json(1);
	}

	public function ajaxGetDepartment() {
		$retVal = Unit::where('parentId', '!=', 0)->get();

		return response()->json($retVal);
	}

	public function ajaxObject() {
		$retVal = AccountPersonalInfo::all();

		return response()->json($retVal);
	}

	public function ajaxDeleteReportAttach(Request $request) {
		$params = $request->all();
		$id = $params['id'];

		$decisionTbl = new DecisionReportAttachment();

		$ret = $decisionTbl->deleteAttach($id);

		return response()->json($ret);
	}

	public function ajaxDelete(Request $request) {
		$params = $request->all();
		$id = $params['id'];
		$ret = DecisionReport::where('id', $id)->delete();

		return response()->json($ret);
	}	
}