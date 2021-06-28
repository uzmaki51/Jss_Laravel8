<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\Decision\DecisionReport;
use App\Models\Finance\BooksList;
use App\Models\Finance\ReportSave;
use App\Models\Finance\WaterList;
use App\Models\Finance\AccountPersonalInfo;
use App\Models\Finance\AccountSetting;

use App\Models\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class FinanceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

	public function getReportList(Request $request) {
		$params = $request->all();

		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForAccountReportDatatable($params);

		return response()->json($reportList);
	}

	public function getAnalysisList(Request $request) {
		$params = $request->all();

		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForAccountAnalysisDatatable($params);

		return response()->json($reportList);
	}

	public function getSettingList(Request $request) {
		$params = $request->all();
		$settingTbl = new AccountSetting();
		$settingList = $settingTbl->getForDatatable($params);

		return response()->json($settingList);
	}

	public function saveSettingList(Request $request) {
		$setting_ids = $request->get('setting_id');
		$setting_account = $request->get('setting_name');
		$setting_contents = $request->get('setting_content');
		$setting_remarks = $request->get('setting_remark');
		$records = AccountSetting::select('*')->get();
		foreach($records as $record) {
			if (!in_array($record->id, $setting_ids)) {
				$record->delete();
			}
		}
		foreach($setting_ids as $index => $item) {
			if ($item == '0') {
				$setting_new_record = new AccountSetting();
				$setting_new_record['account'] = $setting_account[$index];
				$setting_new_record['info'] = $setting_contents[$index];
				$setting_new_record['remark'] = $setting_remarks[$index];
				$setting_new_record->save();
			}
			else {
				AccountSetting::where('id', $item)->update(['account' => $setting_account[$index], 'info' => $setting_contents[$index], 'remark' => $setting_remarks[$index]]);
			}
		}
		return redirect('finance/accounts');
	}

	public function getPersonalInfoList(Request $request) {
		$params = $request->all();
		$infoTbl = new AccountPersonalInfo();
		$infoList = $infoTbl->getForDatatable($params);

		return response()->json($infoList);
	}

	public function savePersonalInfoList(Request $request) {
		$info_ids = $request->get('info_id');
		$info_names = $request->get('info_name');
		$info_contents = $request->get('info_content');
		$info_remarks = $request->get('info_remark');
		$records = AccountPersonalInfo::select('*')->get();
		foreach($records as $record) {
			if (!in_array($record->id, $info_ids)) {
				$record->delete();
			}
		}
		foreach($info_ids as $index => $item) {
			if ($item == '0') {
				$info_new_record = new AccountPersonalInfo();
				$info_new_record['person'] = $info_names[$index];
				$info_new_record['info'] = $info_contents[$index];
				$info_new_record['remark'] = $info_remarks[$index];
				$info_new_record->save();
			}
			else {
				AccountPersonalInfo::where('id', $item)->update(['person' => $info_names[$index], 'info' => $info_contents[$index], 'remark' => $info_remarks[$index]]);
			}
		}
		return redirect('finance/accounts');
	}

    public function books(Request $request)
    {
		$year = $request->get('year');
        $month = $request->get('month');
		if ($year == '') $year = date("Y");
		if ($month == '') $month = date("m");

		$max_item = WaterList::where('year',$year)->select(DB::raw('MAX(book_no) as max_no'))->first();
		$book_no = $max_item['max_no'];
		if (($book_no == null) || ($book_no == '')) $book_no = (int)(substr($year,2) . "0000");

		$start_year = DecisionReport::select(DB::raw('MIN(report_date) as min_date'))->first();
        if(empty($start_year)) {
            $start_year = '2020-01-01';
        } else {
            $start_year = $start_year['min_date'];
        }
        $start_month = date("m", strtotime($start_year));
        $start_year = date("Y", strtotime($start_year));

		$accounts = AccountSetting::all();

        return view('finance.books', [
            'start_year' => $start_year,
			'start_month' => $start_month,
			'year' => $year,
			'month' => $month,
			'book_no' => $book_no,
			'accounts' => $accounts,
        ]);
    }

	public function accounts(Request $request)
    {
		$year = $request->get('year');
        $month = $request->get('month');
		if ($year == '') $year = date("Y");
		if ($month == '') $month = date("m");

		$max_item = WaterList::select(DB::raw('MAX(book_no) as max_no'))->first();
		$book_no = $max_item['max_no'];
		if (($book_no == null) || ($book_no == '')) $book_no = (int)(substr($year,2) . "0000");

		$start_year = DecisionReport::select(DB::raw('MIN(report_date) as min_date'))->first();
        if(empty($start_year)) {
            $start_year = '2020-01-01';
        } else {
            $start_year = $start_year['min_date'];
        }
        $start_month = date("m", strtotime($start_year));
        $start_year = date("Y", strtotime($start_year));
		$accounts = WaterList::select(array('account_type', 'account_name'))->groupBy('account_type')->get();

        return view('finance.accounts', [
            'start_year' => $start_year,
			'start_month' => $start_month,
			'year' => $year,
			'month' => $month,
			'accounts' => $accounts,
			'book_no' => $book_no,
        ]);
    }

	public function saveBookList(Request $request)
	{
		$year = $request->get('select-year');
        $month = $request->get('select-month');

		$report_ids = $request->get('report_id');
		$report_contents = $request->get('report_remark');
		$report_booknos = $request->get('book_no');
		$report_credits = $request->get('credit');
		$report_debits = $request->get('debit');
		$report_rates = $request->get('rate');

		$keep_list = json_decode($request->get('keep_list'));
		for ($i=0;$i<count($keep_list);$i++)
		{
			$record = new WaterList();
			$record['book_no'] = $keep_list[$i]->no;
			$record['ship_no'] = $keep_list[$i]->ship_no;
			$record['content'] = $keep_list[$i]->content;
			$record['year'] = $year;
			$record['month'] = $month;
			$record['register_time'] = $keep_list[$i]->datetime;
			$record['rate'] = $keep_list[$i]->rate;
			$record['pay_type'] = $keep_list[$i]->pay_type;
			$record['account_type'] = $keep_list[$i]->account_type;
			$record['account_name'] = $keep_list[$i]->account_name;
			$record['currency'] = $keep_list[$i]->currency;
			$record['credit'] = $keep_list[$i]->credit;
			$record['debit'] = $keep_list[$i]->debit;
			$record['ship_name'] = $keep_list[$i]->ship_name;
			$record['report_id'] = $keep_list[$i]->report_id;
			$record->save();
		}

		$report_list_record = BooksList::where('year', $year)->where('month', $month)->first();
        if (is_null($report_list_record)) {
            $report_list_record = new BooksList();
        }
        $report_list_record['year'] = $year;
        $report_list_record['month'] = $month;
		$report_list_record->save();

		ReportSave::where('year', $year)->where('month', $month)->delete();
		foreach($report_ids as $index => $item) {
			$report_save_record = new ReportSave();
			$report_original_record = DecisionReport::where('id', $item)->first();
			$report_save_record['orig_id'] = $item;
			$report_save_record['flowid'] = $report_original_record->flowid;
			$report_save_record['type'] = $report_original_record->type;
			$report_save_record['profit_type'] = $report_original_record->profit_type;
			$report_save_record['shipNo'] = $report_original_record->shipNo;
			$report_save_record['voyNo'] = $report_original_record->voyNo;
			$report_save_record['report_date'] = $report_original_record->report_date;
			$report_save_record['report_id'] = $report_original_record->report_id;
			$report_save_record['obj_no'] = $report_original_record->obj_no;
			$report_save_record['obj_name'] = $report_original_record->obj_name;
			$report_save_record['obj_type'] = $report_original_record->obj_type;
			
			
			if ($report_booknos[$index] != '')
			{
				if ($report_original_record->flowid == "Credit") {
					$report_save_record['amount'] = ($report_credits[$index] == '') ? null : str_replace(",","",$report_credits[$index]);
				} else {
					$report_save_record['amount'] = ($report_debits[$index] == '') ? null : str_replace(",","",$report_debits[$index]);
				}
			}
			else
			{
				$report_save_record['amount'] = $report_original_record->amount;
			}

			$report_save_record['currency'] = $report_original_record->currency;
			$report_save_record['creator'] = $report_original_record->creator;
			$report_save_record['recvUser'] = $report_original_record->recvUser;
			$report_save_record['content'] = $report_contents[$index];
			$report_save_record['rate'] = ($report_rates[$index] == '') ? null : $report_rates[$index];
			$report_save_record['book_no'] = ($report_booknos[$index] == "") ? null :str_replace("J-", "", $report_booknos[$index]);
			$report_save_record['attachment'] = $report_original_record->attachment;
			$report_save_record['year'] = $year;
			$report_save_record['month'] = $month;
			$report_save_record['create_time'] = $report_original_record->created_at;

			$report_save_record->save();
		}

		return redirect('finance/books?'.'year='.$year.'&month='.$month);
	}

	public function initBookList(Request $request)
	{
		$params = $request->all();
        $year = $params['year'];
        $month = $params['month'];

        BooksList::where('year', $year)->where('month', $month)->delete();
        ReportSave::where('year', $year)->where('month', $month)->delete();
		WaterList::where('year', $year)->where('month', $month)->delete();

        return 1;
	}

	public function getBookList(Request $request)
	{
		$params = $request->all();
		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForBookDatatable($params);

		return response()->json($reportList);
	}

	public function getWaterList(Request $request)
	{
		$params = $request->all();

		$decideTbl = new DecisionReport();
		$reportList = $decideTbl->getForWaterDatatable($params);

		return response()->json($reportList);
	}

	public function getList(Request $request)
	{
		$backupTbl = new BackupDB();
		$result = $backupTbl->getForDatatable($request->all());

		return response()->json($result);
	}

	public function add(Request $request)
	{
		$backupTbl = new BackupDB();
		$result = $backupTbl->addTransaction($request->all());

		return response()->json($result);
	}

	public function backup(Request $request)
	{
		$backupTbl = new BackupDB();
		$result = $backupTbl->runBackup($request->all());

		return response()->json($result);
	}

    public function restore(Request $request)
	{
		$backupTbl = new BackupDB();
		$result = $backupTbl->runRestore($request->all());

		return response()->json($result);
	}
}