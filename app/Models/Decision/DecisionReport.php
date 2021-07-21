<?php
/**
 * Created by PhpStorm.
 * User: CCJ
 * Date: 4/21/2017
 * Time: 22:28
 */

namespace App\Models\Decision;


use App\Models\Convert\VoyLog;
use App\Models\Operations\AcItem;
use App\Models\ShipManage\ShipRegister;
use App\Models\Finance\BooksList;
use App\Models\Finance\ReportSave;
use App\Models\Finance\WaterList;
use App\Models\Finance\ExpectedCosts;
use App\Models\Operations\Cp;
use App\Models\ShipTechnique\ShipPort;
use App\Models\Operations\Cargo;
use App\Models\Finance\AccountPersonalInfo;
use App\Models\Decision\DecisionReportAttachment;
use App\Models\Member\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;

class DecisionReport extends Model {
	protected $table = 'tb_decision_report';
	protected $table_register_ship = 'tb_ship_register';

	public function getForSavedBookDatatable($params, $year, $month) {
		//$selector = ReportSave::where('year', $year)->where('month', $month);
		$next_year = $year;
        $next_month = $month;
        if ($month == 12) {
            $next_month = 1;
            $next_year ++;
        }
        else
        {
            $next_month = $month + 1;
        }
		$now = date('Y-m-d', strtotime("$year-$month-1"));
		$next = date('Y-m-d', strtotime("$next_year-$next_month-1"));
		//$next = date('Y-m-d', strtotime('-1 day', strtotime($next)));
			
		$selector = ReportSave::where('report_date', '>=', $now)->where('report_date', '<', $next);

        $recordsFiltered = $selector->count();
		$records = $selector->orderBy('report_id', 'asc')->get();
		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['id'] = $record->orig_id;
			$newArr[$newindex]['flowid'] = $record->flowid;
			$newArr[$newindex]['report_no'] = $record->report_id;
			$newArr[$newindex]['book_no'] = $record->book_no == null ? '' : $record->book_no;
			$newArr[$newindex]['datetime'] = $record->report_date;

			if ($record->obj_type == 1) {
				$newArr[$newindex]['ship_no'] = $record->shipNo;
				$newArr[$newindex]['voyNo'] = $record->voyNo;

				$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
				$newArr[$newindex]['obj'] = $ship->NickName;
			}
			else
			{
				$newArr[$newindex]['ship_no'] = $record->obj_no;
				$newArr[$newindex]['voyNo'] = '';
				$newArr[$newindex]['obj'] = $record->obj_name;
			}
			/*
			$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
			$newArr[$newindex]['obj'] = $ship->NickName;
			$newArr[$newindex]['ship_no'] = $record->shipNo;
			$contract = VoyLog::where('id', $record->voyNo)->first();
			$newArr[$newindex]['voyNo'] = $contract->CP_ID;
			*/
			$newArr[$newindex]['currency'] = $record->currency == 'USD' ? "$" : "¥";
			$newArr[$newindex]['type'] = $record->type;
			$newArr[$newindex]['profit_type'] = $record->profit_type;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['amount'] = $record->amount;
			$newArr[$newindex]['rate'] = $record->rate == null ? '' : $record->rate;
			$attachment = DecisionReportAttachment::where('reportId', $record->orig_id)->first();
			$newArr[$newindex]['attachment'] = null;
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			$newindex ++;
		}

		///////////////// Need to Optimize
		$selector = DB::table($this->table)
			->orderBy('report_id', 'asc')
			->where('state', 1);

		$selector->where('report_date', '>=', $now)->where('report_date', '<', $next);
		$recordsFiltered = $selector->count();
		$records = $selector->get();
		foreach($records as $index => $record) {
			$report_original_record = ReportSave::where('orig_id', $record->id)->first();
			if (!empty($report_original_record)) continue;

			$newArr[$newindex]['id'] = $record->id;
			$newArr[$newindex]['flowid'] = $record->flowid;
			$newArr[$newindex]['report_no'] = $record->report_id;
			$newArr[$newindex]['book_no'] = '';
			$newArr[$newindex]['datetime'] = $record->report_date;
			/*
			$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
			$newArr[$newindex]['obj'] = $ship->NickName;
			$newArr[$newindex]['ship_no'] = $record->shipNo;
			$contract = VoyLog::where('id', $record->voyNo)->first();
			$newArr[$newindex]['voyNo'] = $contract->CP_ID;
			*/
			if ($record->obj_type == 1) {
				$newArr[$newindex]['ship_no'] = $record->shipNo;
				$newArr[$newindex]['voyNo'] = $record->voyNo;

				$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
				$newArr[$newindex]['obj'] = $ship->NickName;
			}
			else
			{
				$newArr[$newindex]['ship_no'] = $record->obj_no;
				$newArr[$newindex]['voyNo'] = '';
				$newArr[$newindex]['obj'] = $record->obj_name;
			}
			$newArr[$newindex]['currency'] = $record->currency == 'USD' ? "$" : "¥";
			$newArr[$newindex]['type'] = $record->type;
			$newArr[$newindex]['profit_type'] = $record->profit_type;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['amount'] = $record->amount;
			$newArr[$newindex]['rate'] = '';
			$attachment = DecisionReportAttachment::where('reportId', $record->id)->first();
			$newArr[$newindex]['attachment'] = null;
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			
			$newindex ++;
		}

		$book_no = $this->getBookNo($year);
		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => false,
            'data' => $newArr,
			'book_no' => $book_no,
            'error' => 0,
        ];
	}

	public function calcProfitList($shipid, $year, $month) {
		$newArr = [];
		$count = 0;
		/*
		$voyNo_from = substr($year, 2, 2) . '00';
		$voyNo_to = substr($year, 2, 2) + 1;
		$voyNo_to = $voyNo_to . '00';
		*/
		$selector = ReportSave::where('type', 0)->where('shipNo',$shipid)->where('year',$year)->whereNotNull('book_no');
		if ($month != null) {
			$selector = $selector->where('month', $month);
		}
		// 办公费:13, 兑换:14
		$selector = $selector->whereNotIn('profit_type',[13,14])
		->groupBy('flowid','profit_type')
		->selectRaw('sum(CASE WHEN currency="CNY" THEN amount/rate ELSE amount END) as sum, flowid, profit_type, currency')
		->groupBy('flowid');

		$cost_records = $selector->get();
		$credit_sum = 0;
		$debit_sum = 0;
		$profit_sum = 0;

		foreach($cost_records as $cost) {
			if ($cost->flowid == REPORT_TYPE_EVIDENCE_IN) {
				$credit_sum += $cost->sum;
			}
			else if ($cost->flowid == REPORT_TYPE_EVIDENCE_OUT) {
				$newArr[$cost->profit_type] = $cost->sum;
				$debit_sum += $cost->sum;
			}
		}
		$profit_sum = $credit_sum - $debit_sum;
		$result['data'] = $newArr;
		$result['credit_sum'] = $credit_sum;
		$result['debit_sum'] = $debit_sum;
		$result['profit_sum'] = $profit_sum;

		return $result;
	}

	/// incomeExpenseAll -> Table
	public function getListByAll($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
			!isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$shipids = $params['shipId'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$shipids = explode(",",$params['columns'][2]['search']['value']);
		}

		// Prev Year Profit
		$prevProfit = [];
		foreach($shipids as $shipid) {
			$prevProfit[$shipid] = $this->calcProfitList($shipid, $year-1, null);
		}

		// Profit Per Every Month 
		$voyNo_from = substr($year, 2, 2) . '00';
		$voyNo_to = substr($year, 2, 2) + 1;
		$voyNo_to = $voyNo_to . '00';

		//$selector = ReportSave::where('type', 0)->whereIn('shipNo',$shipids)->where('voyNo','>=', $voyNo_from)->where('voyNo','<',$voyNo_to)->whereNotNull('book_no');
		$selector = ReportSave::where('type', 0)->whereIn('shipNo',$shipids)->where('year',$year)->whereNotNull('book_no');
		$selector = $selector->whereNotIn('profit_type',[13,14])
		->selectRaw('sum(CASE WHEN currency="CNY" THEN amount/rate ELSE amount END) as sum, flowid, profit_type, month, shipNo')
		->groupBy('month', 'flowid','profit_type','shipNo');
		$records = $selector->get();

		$result = [];
		foreach($shipids as $shipid)
		{
			$result[$shipid]['prevProfit'] = 0;
			for ($i=0;$i<12;$i++)
			{
				$result[$shipid]['months'][$i] = 0;
				$result[$shipid]['sum_months'][$i] = 0;
			}
			for ($i=1;$i<16;$i++)
			{
				$result[$shipid]['debits'][$i] = 0;
			}
			$result[$shipid]['credit_sum'] = 0;
			$result[$shipid]['debit_sum'] = 0;
		}
		foreach($records as $index => $record) {
			if ($record['flowid'] == "Credit") {
				$result[$record['shipNo']]['months'][$record['month']-1] += $record['sum'];
				$result[$record['shipNo']]['credit_sum'] += $record['sum'];
			}
			else if ($record['flowid'] == "Debit") {
				$result[$record['shipNo']]['months'][$record['month']-1] -= $record['sum'];
				$result[$record['shipNo']]['debits'][$record['profit_type']] += $record['sum'];
				$result[$record['shipNo']]['debit_sum'] += $record['sum'];
			}
		}
		foreach($shipids as $shipid) {
			if (isset($prevProfit[$shipid])) {
				$result[$shipid]['prevProfit'] = $prevProfit[$shipid]['profit_sum'];
			} else {
				$result[$shipid]['prevProfit'] = 0;
			}

			$sum = 0;
			for ($i=0;$i<12;$i++) {
				$sum += $result[$shipid]['months'][$i];
				$result[$shipid]['sum_months'][$i] = $sum;
			}
		}

		return $result;
	}

	public function getIncome($shipid, $voyNo) {
		$selector = ReportSave::where('type', 0)->where('shipNo',$shipid)->where('voyNo', $voyNo)->whereNotNull('book_no')
				->groupBy('flowid','profit_type')
				->selectRaw('sum(CASE WHEN tb_decision_report_save.currency="CNY" THEN tb_decision_report_save.amount/tb_decision_report_save.rate ELSE tb_decision_report_save.amount END) as sum, tb_decision_report_save.flowid, tb_decision_report_save.profit_type, tb_decision_report_save.currency')
				->groupBy('flowid','profit_type');

			$selector = $selector->leftJoin("tbl_cp", function($join) {
				$join->on('tbl_cp.Voy_No', '=', 'tb_decision_report_save.voyNo');
				$join->on('tbl_cp.Ship_ID', '=', 'tb_decision_report_save.shipNo');
			})->whereIn("tbl_cp.CP_kind",["TC","VOY"]);

			$cost_records = $selector->get();
			
			$newArr = [];
			$credit_sum = 0;
			$debit_sum = 0;
			foreach($cost_records as $cost) {
				if ($cost->flowid == REPORT_TYPE_EVIDENCE_IN) {
					$credit_sum += $cost->sum;
				}
				else if ($cost->flowid == REPORT_TYPE_EVIDENCE_OUT && $cost->profit_type != 13 && $cost->profit_type != 14)
				{
					$newArr[$cost->profit_type] = $cost->sum;
					$debit_sum += $cost->sum;
				}
			}

		return [$credit_sum, $debit_sum, $newArr];
	}
	
	/// incomeExpense for three years-> Table, Graph (similar to getIncomeExportList)
	public function getIncomeExportListForPast($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == ''
        ) {
        	$shipid = $params['shipId'];
        }
		else
		{
			$shipid = $params['columns'][1]['search']['value'];
		}

		$start_year = DecisionReport::select(DB::raw('MAX(report_date) as max_date'))->first();
        if(empty($start_year)) {
            $start_year = date("Y");
        } else {
            $start_year = substr($start_year['max_date'],0,4);
        }
		$total_profit = 0;
		$start_year = intval($start_year);
		$records = [];
		$index = 0;
		for ($year=$start_year-2;$year<=$start_year;$year ++) {
			$voyNo_from = substr($year, 2, 2) . '00';
			$voyNo_to = substr($year, 2, 2) + 1;
			$voyNo_to = $voyNo_to . '00';

			$selector = ReportSave::where('type', 0)->where('shipNo',$shipid)->where('voyNo','>=', $voyNo_from)->where('voyNo','<',$voyNo_to)->whereNotNull('book_no')
				->groupBy('flowid','profit_type')
				->selectRaw('sum(CASE WHEN tb_decision_report_save.currency="CNY" THEN tb_decision_report_save.amount/tb_decision_report_save.rate ELSE tb_decision_report_save.amount END) as sum, tb_decision_report_save.flowid, tb_decision_report_save.profit_type, tb_decision_report_save.currency')
				->groupBy('flowid','profit_type');

			$selector = $selector->leftJoin("tbl_cp", function($join) {
				$join->on('tbl_cp.Voy_No', '=', 'tb_decision_report_save.voyNo');
				$join->on('tbl_cp.Ship_ID', '=', 'tb_decision_report_save.shipNo');
			})->whereIn("tbl_cp.CP_kind",["TC","VOY","NON"]);

			$cost_records = $selector->get();
			
			$newArr = [];
			$credit_sum = 0;
			$debit_sum = 0;
			$profit_sum = 0;
			foreach($cost_records as $cost) {
				if ($cost->flowid == REPORT_TYPE_EVIDENCE_IN) {
					$credit_sum += $cost->sum;
				}
				else if ($cost->flowid == REPORT_TYPE_EVIDENCE_OUT && $cost->profit_type != 13 && $cost->profit_type != 14)
				{
					$newArr[$cost->profit_type] = $cost->sum;
					$debit_sum += $cost->sum;
				}
			}
			$records[$index]['year'] = $year;
			$records[$index]['credit_sum'] = $credit_sum;
			$records[$index]['debit_sum'] = $debit_sum;
			$records[$index]['profit_sum'] = $credit_sum - $debit_sum;
			$total_profit += $records[$index]['profit_sum'];
			$records[$index]['total_profit'] = $total_profit;
			$records[$index]['debit_list'] = $newArr;

			$from_date = $year . "-01-01";
			$to_date = $year . "-12-31";

			//$selector = CP::where('CP_Date','>=',$from_date)->where('CP_Date','<=',$to_date)->where('Ship_ID',$shipid)
			$selector = CP::where('Voy_No','>=', $voyNo_from)->where('Voy_No','<',$voyNo_to)->where('Ship_ID',$shipid)
				->groupBy('CP_kind')->selectRaw('count(CP_kind) as count, CP_kind');
			$cp_records = $selector->get();
			foreach($cp_records as $count) {
				if ($count->CP_kind == "TC") $records[$index]['TC_count'] = $count->count;
				else if ($count->CP_kind == "VOY") $records[$index]['VOY_count'] = $count->count;
				else if ($count->CP_kind == "NON") $records[$index]['NON_count'] = $count->count;
			}
			//$min_date = VoyLog::where('Ship_ID', $shipid)->where('Voy_Date', '>=',$from_date)->where('Voy_Date', '<=', $to_date)
			$min_date = VoyLog::where('Ship_ID', $shipid)->where('CP_ID','>=', $voyNo_from)->where('CP_ID','<',$voyNo_to)
							  ->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->select(DB::raw('MIN(Voy_Date) as min_date,tbl_voy_log.*'))->first();
			//$max_date = VoyLog::where('Ship_ID', $shipid)->where('Voy_Date', '>=',$from_date)->where('Voy_Date', '<=', $to_date)
			$max_date = VoyLog::where('Ship_ID', $shipid)->where('CP_ID','>=', $voyNo_from)->where('CP_ID','<',$voyNo_to)
							  ->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->select(DB::raw('MAX(Voy_Date) as max_date,tbl_voy_log.*'))->first();

			if($min_date->min_date == $max_date->max_date) {
				//$min_date = VoyLog::where('Ship_ID', $shipid)->where('Voy_Date', '>=',$from_date)->where('Voy_Date', '<=', $to_date)
				$min_date = VoyLog::where('Ship_ID', $shipid)->where('CP_ID','>=', $voyNo_from)->where('CP_ID','<',$voyNo_to)
				->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc')->orderBy('GMT', 'asc')->first();
				if ($min_date == null) $min_date = false;
			}
			else
			{
				if($min_date->min_date == null)
					$min_date = false;
				if($max_date->max_date == null)
					$max_date = false;
			}
			if ($max_date->max_date == null) $max_date = false;

			$records[$index]['min_date'] = $min_date;
			$records[$index]['max_date'] = $max_date;
			$index ++;
		}

		$costs = ExpectedCosts::where('shipNo', $shipid)->first();
		if (isset($params['draw'])) {
			return [
				'draw' => $params['draw']+0,
				'recordsTotal' => DB::table($this->table)->count(),
				'recordsFiltered' => $index,
				'data' => $records,
				'costs' => $costs,
				'error' => 0,
			];
		} else {
			return $records;
		}
		
	}

	/// incomeExpense -> Table, Graph
	public function getIncomeExportList($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
			!isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$shipid = $params['shipId'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$shipid = $params['columns'][2]['search']['value'];
		}

		//$start = date('Y-m-d', strtotime("$year-01-01"));
		//$end = date('Y-m-d', strtotime("$year-12-31"));
		//$selector = CP::where('Ship_ID', $shipid)->where('CP_Date', '<=' , $end)->where('CP_Date', '>=', $start);
		$voyNo_from = substr($year, 2, 2) . '00';
		$voyNo_to = substr($year, 2, 2) + 1;
		$voyNo_to = $voyNo_to . '00';

		$selector = CP::where('Ship_ID', $shipid)->where('Voy_No','>=', $voyNo_from)->where('Voy_No','<',$voyNo_to);
		$records = $selector->orderBy('Voy_No', 'asc')->get();
		$count = $selector->count();
		$total_profit = 0;

		foreach($records as $index => $record) {
			//$selector = ReportSave::where('type', 0)->where('shipNo',$shipid)->where('voyNo',$record->Voy_No)
			//$selector = ReportSave::where('type', 0)->where('shipNo',$shipid)->where('voyNo',$record->Voy_No)->where('year', '<=' , $end)->where('report_date', '>=', $start)
			//$selector = ReportSave::where('type', 0)->where('shipNo',$shipid)->where('voyNo',$record->Voy_No)->where('year', $year)->whereNotNull('book_no')
			$selector = ReportSave::where('type', 0)->where('shipNo',$shipid)->where('voyNo',$record->Voy_No)->whereNotNull('book_no')
				->groupBy('flowid','profit_type')
				->selectRaw('sum(CASE WHEN currency="CNY" THEN amount/rate ELSE amount END) as sum, flowid, profit_type, currency')
				->groupBy('flowid');
			$cost_records = $selector->get();
			$newArr = [];
			$credit_sum = 0;
			$debit_sum = 0;
			$profit_sum = 0;
			foreach($cost_records as $cost) {
				if ($cost->flowid == REPORT_TYPE_EVIDENCE_IN) {
					$credit_sum += $cost->sum;
				}
				else if ($cost->flowid == REPORT_TYPE_EVIDENCE_OUT && $cost->profit_type != 13 && $cost->profit_type != 14)
				{

					$newArr[$cost->profit_type] = $cost->sum;
					$debit_sum += $cost->sum;
				}
			}
			$record->credit_sum = $credit_sum;
			$record->debit_sum = $debit_sum;
			$record->profit_sum = $credit_sum - $debit_sum;
			$total_profit += $record->profit_sum;
			$record->total_profit = $total_profit;
			$record->debit_list = $newArr;

			$min_date = VoyLog::where('Ship_ID', $shipid)->where('CP_ID', '<',$record->Voy_No)->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->orderBy('Voy_Date', 'desc')->orderBy('Voy_Hour', 'desc')->orderBy('Voy_Minute', 'desc')->orderBy('GMT', 'desc')->first();
			$max_date = VoyLog::where('Ship_ID', $shipid)->where('CP_ID', $record->Voy_No)->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->orderBy('Voy_Date', 'desc')->orderBy('Voy_Hour', 'desc')->orderBy('Voy_Minute', 'desc')->orderBy('GMT', 'desc')->first();
			if($min_date == false || $min_date == null) {
				$min_date = VoyLog::where('Ship_ID', $shipid)->where('CP_ID', $record->Voy_No)->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc')->orderBy('GMT', 'asc')->first();
				if($min_date == null)
					$min_date = false;
			}
	
			if($max_date == false || $max_date == null)
				$max_date = false;

			$record->min_date = $min_date;
			$record->max_date = $max_date;
		}
		
		if (isset($params['draw'])) {
			return [
				'draw' => $params['draw']+0,
				'recordsTotal' => DB::table($this->table)->count(),
				'recordsFiltered' => $count,
				'data' => $records,
				'error' => 0,
			];
		} else {
			return $records;
		}
		
	}

	// incomeExpense -> SOA
	public function getListBySOA($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == '' ||
			!isset($params['columns'][3]['search']['value']) ||
            $params['columns'][3]['search']['value'] == ''
        ) {
            $shipid = $params['shipId'];
        	$voyNo = $params['voy_no'];
			$currency = $params['currency'];
        }
		else
		{
			$shipid = $params['columns'][1]['search']['value'];
			$voyNo = $params['columns'][2]['search']['value'];
			$currency = $params['columns'][3]['search']['value'];
		}
		// 办公费:13, 兑换:14
		$selector = ReportSave::where('type', 0)->whereNotIn('profit_type',[13,14])->where('shipNo', $shipid)->where('voyNo', $voyNo)->whereNotNull('book_no');
		$records = $selector->orderBy('id', 'asc')->get();
		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['date'] = $record->report_date;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['flowid'] = $record->flowid;
			$newArr[$newindex]['profit_type'] = $record->profit_type;

			$amount = $record->amount;
			if ($currency == 'USD') {
				if ($record->currency == 'CNY') $amount = $amount / $record->rate;
			} else if($currency == 'CNY') {
				if ($record->currency == 'USD') $amount = $record->rate * $amount;
			}

			if ($record->flowid==REPORT_TYPE_EVIDENCE_IN) {
				$newArr[$newindex]['credit'] = $amount;
				$newArr[$newindex]['debit'] = '';
			} else
			{
				$newArr[$newindex]['credit'] = '';
				$newArr[$newindex]['debit'] = $amount;
			}
			$newArr[$newindex]['rate'] = $record->rate;
			
			$attachment = DecisionReportAttachment::where('reportId', $record->orig_id)->first();
			$newArr[$newindex]['attachment'] = null;
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			$newindex ++;
		}
		
		$voy_info = CP::where('Ship_ID', $shipid)->where('Voy_No',$voyNo)->first();
		$LPort = '';
		$DPort = '';
		$Cargo = '';
		if (!empty($voy_info)) {
			$LPort = $voy_info->LPort;
			$LPort = explode(',', $LPort);
			$LPort = ShipPort::whereIn('id', $LPort)->get();
			$tmp = '';
			foreach($LPort as $port)
				$tmp .= $port->Port_En . ' (' . $port->Port_Cn . ') / ';
			$LPort = substr($tmp, 0, strlen($tmp) - 3);

			$DPort = $voy_info->DPort;
			$DPort = explode(',', $DPort);
			$DPort = ShipPort::whereIn('id', $DPort)->get();
			$tmp = '';
			foreach($DPort as $port)
				$tmp .= $port->Port_En . ' (' . $port->Port_Cn . ') / ';
			$DPort = substr($tmp, 0, strlen($tmp) - 3);

			$Cargo = $voy_info->Cargo;
			$Cargo = explode(',', $Cargo);
			$Cargo = Cargo::whereIn('id', $Cargo)->get();
			$tmp = '';
			foreach($Cargo as $item)
				$tmp .=  $item->name . ', ';
			$Cargo = substr($tmp, 0, strlen($tmp) - 2);
		}

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => false,
            'data' => $newArr,
			'voy_info' => $voy_info,
			'LPort' => $LPort,
			'DPort' => $DPort,
			'Cargo' => $Cargo,
            'error' => 0,
        ];
	}

	public function getBookNo($year) {
		$max_item = WaterList::where('year',$year)->select(DB::raw('MAX(book_no) as max_no'))->first();
		$book_no = $max_item['max_no'];
		if (($book_no == null) || ($book_no == '')) $book_no = (int)(substr($year,2) . "0000");

		return $book_no;
	}
	public function getForBookDatatable($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$month = $params['month'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
		}

		$report_list_record = BooksList::where('year', $year)->where('month', $month)->first();
        if (!is_null($report_list_record)) {
            return $this->getForSavedBookDatatable($params, $year, $month);
        }

		$selector = DB::table($this->table)
			->orderBy('report_id', 'asc')
			->where('state', 1);

		$next_year = $year;
        $next_month = $month;
        if ($month == 12) {
            $next_month = 1;
            $next_year ++;
        }
        else
        {
            $next_month = $month + 1;
        }
		$now = date('Y-m-d', strtotime("$year-$month-1"));
		$next = date('Y-m-d', strtotime("$next_year-$next_month-1"));
		//$next = date('Y-m-d', strtotime('-1 day', strtotime($next)));
			
		$selector->where('report_date', '>=', $now)->where('report_date', '<', $next);
		$recordsFiltered = $selector->count();
		$records = $selector->get();

		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['id'] = $record->id;
			$newArr[$newindex]['flowid'] = $record->flowid;
			$newArr[$newindex]['report_no'] = $record->report_id;
			$newArr[$newindex]['book_no'] = '';
			$newArr[$newindex]['datetime'] = $record->report_date;
			
			if ($record->obj_type == 1) {
				$newArr[$newindex]['ship_no'] = $record->shipNo;
				$newArr[$newindex]['voyNo'] = $record->voyNo;

				$ship = ShipRegister::where('IMO_No', $record->shipNo)->first();
				$newArr[$newindex]['obj'] = $ship->NickName;
			}
			else
			{
				$newArr[$newindex]['ship_no'] = $record->obj_no;
				$newArr[$newindex]['voyNo'] = '';
				$newArr[$newindex]['obj'] = $record->obj_name;
			}

			$newArr[$newindex]['currency'] = $record->currency == 'USD' ? "$" : "¥";
			$newArr[$newindex]['type'] = $record->type;
			$newArr[$newindex]['profit_type'] = $record->profit_type;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['amount'] = $record->amount;
			$newArr[$newindex]['rate'] = '';
			$newArr[$newindex]['attachment'] = null;
			$attachment = DecisionReportAttachment::where('reportId', $record->id)->first();
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			
			$newindex ++;
		}

		$book_no = $this->getBookNo($year);
		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => true,
            'data' => $newArr,
			'book_no' => $book_no,
            'error' => 0,
        ];
	}

	public function getForDatatable($params, $status = null) {
		$user = Auth::user();
		$is_booker = $user->pos;
		$years = $this->getYearList();
		$selector = DB::table($this->table)
			->orderBy('report_id', 'desc')
			->select('*');

		if($status != null)
			$selector->where('state', '=', $status);
		else {
			if($is_booker == USER_POS_ACCOUNTER)
				$selector->where('state', REPORT_STATUS_ACCEPT);
			else
				$selector->where('state', '!=', REPORT_STATUS_DRAFT);
		}
			
		if($is_booker == USER_POS_ACCOUNTER) {
			$selector->whereIn('flowid', [REPORT_TYPE_EVIDENCE_OUT, REPORT_TYPE_EVIDENCE_IN]);
		} else if($user->isAdmin != SUPER_ADMIN)
			$selector->where('creator', '=', $user->id);

		if (isset($params['columns'][0]['search']['value'])
			&& $params['columns'][0]['search']['value'] !== ''
		) {
			$selector->whereRaw(DB::raw('mid(report_date, 1, 4) like ' . $params['columns'][0]['search']['value']));
		} else {
			if($status == null)
				$selector->whereRaw(DB::raw('mid(report_date, 1, 4) like ' . $years[0]));
		}

		if (isset($params['columns'][1]['search']['value'])
			&& $params['columns'][1]['search']['value'] !== ''
		) {
			$selector->whereRaw(DB::raw('mid(report_date, 6, 7) = ' . sprintf("%'02d\n", $params['columns'][1]['search']['value'])));
		}

		if (isset($params['columns'][2]['search']['value'])
			&& $params['columns'][2]['search']['value'] !== ''
		) {
			$obj = $params['columns'][2]['search']['value'];
			if($obj == 'OBJ') {
				$selector->where('obj_type', OBJECT_TYPE_PERSON);
			} else {
				$selector->where('obj_type', OBJECT_TYPE_SHIP);
				$selector->where('shipNo', $obj);
			}
		}

		// number of filtered records
		$recordsFiltered = $selector->count();
		
		// offset & limit
		if (!empty($params['start']) && $params['start'] > 0) {
			$selector->skip($params['start']);
		}

		if (!empty($params['length']) && $params['length'] > 0) {
			$selector->take($params['length']);
		}

		// get records
		$records = $selector->get();

		if($user->isAdmin == SUPER_ADMIN) {
			$ids = [];
			foreach($records as $key => $item) {
				$ids[] = $item->id;
			}
			self::whereIn('id', $ids)->update([
				'readed_at'		=> date('Y-m-d H:i:s')
			]);
		}
		
		foreach($records as $key => $item) {
			if($item->obj_type == OBJECT_TYPE_SHIP) {
				$shipInfo = ShipRegister::where('IMO_No', $item->shipNo)->first();
				if($shipInfo == null)
					$shipName = '';
				else {
					$shipName = $shipInfo->NickName == '' ? $shipInfo->shipName_En : $shipInfo->NickName;
				}
			} else {
				$personInfo = AccountPersonalInfo::where('id', $item->obj_no)->first();
				if($personInfo == null) 
					$shipName = '';
				else {
					$shipName = $personInfo->person;
				}
			}
				
			$attach = DecisionReportAttachment::where('reportId', $item->id)->first();
			if($attach != null)
				$records[$key]->attach_link = $attach->file_link;
			else
				$records[$key]->attach_link = '';

			if(ACItem::where('id', $item->profit_type)->first())
				$profit = ACItem::where('id', $item->profit_type)->first()->AC_Item_Cn;
			else
				$profit = '';

			$retVal = Unit::where('parentId', '!=', 0)->where('id', $item->depart_id)->first();
			if($retVal == null)
				$records[$key]->depart_name = '';
			else
				$records[$key]->depart_name = $retVal->title;

			$reporter = User::where('id', $item->creator)->first()->realname;

			$records[$key]->shipName = $shipName;
			$records[$key]->realname = $reporter;

			if($user->isAdmin == SUPER_ADMIN) {
				if(isset($_saved_ids) && $_saved_ids != null) {
					if(isset($_saved_ids[$key]) && $records[$key]->id == $_saved_ids[$key])
						$records[$key]->readed_at = date('Y-m-d H:i:s');
				}
			}
				
		}
		
		return [
			'draw' => $params['draw']+0,
			'recordsTotal' => DB::table($this->table)->count(),
			'recordsFiltered' => $recordsFiltered,
			'data' => $records,
			'error' => 0,
		];
	}

	public function getForAccountReportDatatable($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$month = $params['month'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
		}

		if ($month == 0)
			$selector = WaterList::where('year', $year);
		else
			$selector = WaterList::where('year', $year)->where('month', $month);
		
		$selector = $selector->groupBy('account_type')->selectRaw('sum(credit) as credit, sum(debit) as debit, max(register_time) as update_date, currency, account_type, account_name')->groupBy('currency');
		$recordsFiltered = $selector->count();
		$records = $selector->get();

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $recordsFiltered,
            'data' => $records,
            'error' => 0,
        ];
	}

	public function getForAccountAnalysisDatatable($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == '' ||
			!isset($params['columns'][3]['search']['value']) ||
            $params['columns'][3]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$month = $params['month'];
			$account_type = $params['account'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
			$account_type = $params['columns'][3]['search']['value'];
		}

		if ($month == 0) {
			$selector = WaterList::where('year', $year)->where('account_type',$account_type)->select('*');
		} else {
			$selector = WaterList::where('year', $year)->where('month', $month)->where('account_type',$account_type)->select('*');
		}
		$recordsFiltered = $selector->count();
		$records = $selector->get();

		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['book_no'] = $record->book_no;
			$newArr[$newindex]['ship_name'] = $record->ship_name;
			//$newArr[$newindex]['datetime'] = $record->created_at;
			$newArr[$newindex]['datetime'] = $record->register_time;
			//$newArr[$newindex]['report_no'] = $record->id;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['currency'] = $record->currency;
			$newArr[$newindex]['credit'] = $record->credit;
			$newArr[$newindex]['debit'] = $record->debit;
			$newArr[$newindex]['rate'] = $record->rate;
			$newArr[$newindex]['pay_type'] = $record->pay_type;
			//$newArr[$newindex]['account_type'] = $record->account_type;
			$newArr[$newindex]['account_name'] = $record->account_name;
			$newArr[$newindex]['report_id'] = $record->report_id;
			$attachment = DecisionReportAttachment::where('reportId', $record->report_id)->first();
			$newArr[$newindex]['attachment'] = null;
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			$newindex ++;
		}

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => true,
            'data' => $newArr,
            'error' => 0,
        ];
	}

	public function getForWaterDatatable($params) {
		if (!isset($params['columns'][1]['search']['value']) ||
            $params['columns'][1]['search']['value'] == '' ||
            !isset($params['columns'][2]['search']['value']) ||
            $params['columns'][2]['search']['value'] == ''
        ) {
            $year = $params['year'];
        	$month = $params['month'];
        }
		else
		{
			$year = $params['columns'][1]['search']['value'];
			$month = $params['columns'][2]['search']['value'];
		}

		$selector = WaterList::where('year', $year)->where('month', $month)->select('*');
		$recordsFiltered = $selector->count();
		$records = $selector->get();

		$newArr = [];
        $newindex = 0;
		foreach($records as $index => $record) {
			$newArr[$newindex]['book_no'] = $record->book_no;
			$newArr[$newindex]['ship_name'] = $record->ship_name;
			//$newArr[$newindex]['datetime'] = $record->created_at;
			$newArr[$newindex]['datetime'] = $record->register_time;
			//$newArr[$newindex]['report_no'] = $record->id;
			$newArr[$newindex]['content'] = $record->content;
			$newArr[$newindex]['currency'] = $record->currency;
			$newArr[$newindex]['credit'] = $record->credit;
			$newArr[$newindex]['debit'] = $record->debit;
			$newArr[$newindex]['rate'] = $record->rate;
			$newArr[$newindex]['pay_type'] = $record->pay_type;
			//$newArr[$newindex]['account_type'] = $record->account_type;
			$newArr[$newindex]['account_name'] = $record->account_name;
			$newArr[$newindex]['report_id'] = $record->report_id;
			$attachment = DecisionReportAttachment::where('reportId', $record->report_id)->first();
			$newArr[$newindex]['attachment'] = null;
			if (!empty($attachment)) {
				$newArr[$newindex]['attachment'] = $attachment->file_link;
			}
			$newindex ++;
		}

		return [
            'draw' => $params['draw']+0,
            'recordsTotal' => DB::table($this->table)->count(),
            'recordsFiltered' => $newindex,
            'original' => true,
            'data' => $newArr,
            'error' => 0,
        ];
	}

	public function decideReport($params) {
		$ret = DB::table($this->table)
			->where('id', $params['reportId'])
			->update([
				'state' => $params['decideType']
			]);

		return $ret;
	}

	public function getReportDetail($params) {
		$selector = DB::table($this->table)
			->where('id', $params['reportId'])
			->select('*');
		$result = $selector->first();

		if(isset($params['reportId'])) {
			$attachmentList = DecisionReportAttachment::where('reportId', $params['reportId'])->first();
		}

		return array(
			'list'      => $result,
			'attach'    => $attachmentList
		);
	}

	public function noAttachments($params) {
		$selector = DB::table($this->table)->where('attachment',0)->orWhere('attachment',null);

		// number of filtered records
		
		$totalCount = $selector->count();
		
		$recordsFiltered = $selector->count();

		// offset & limit
		if (!empty($params['start']) && $params['start'] > 0) {
			$selector->skip($params['start']);
		}

		if (!empty($params['length']) && $params['length'] > 0) {
			$selector->take($params['length']);
		}

		$records = $selector->get();

		return [
			'draw' => $params['draw']+0,
			'recordsTotal' =>  DB::table($this->table)->count(),
			'recordsFiltered' => $recordsFiltered,
			'data' => $records,
			'error' => 0,
		];
	}

	public function getYearList() {
		$yearList = [];
        $info = self::orderBy('report_date', 'asc')->first();
        if($info == null) {
            $baseYear = date('Y');
        } else {
            $baseYear = substr($info->report_date, 0, 4);
        }

        for($year = date('Y'); $year >= $baseYear; $year --) {
            $yearList[] = $year;
        }

        return $yearList;
	}

	public function getLastId() {
		$user = Auth::user();
		$user_id = $user->id;

		$last = self::where('creator', $user_id)->where('state', REPORT_STATUS_REQUEST)->orderBy('report_id', 'desc')->first();
		if($last == null) {
			return 0;
		}

		return $last->id;
	}

	public function checkReport($isAdmin) {
		if($isAdmin == SUPER_ADMIN) {
			$isExist = self::where('state', REPORT_STATUS_REQUEST)->whereNull('readed_at')->get();
			if(!isset($isExist) || count($isExist) == 0)
				return false;
			else
				return count($isExist);
		} else {
			return false;
		}
	}
}