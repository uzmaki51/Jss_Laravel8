<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 4/10/2017
 * Time: 4:15 PM
 */
namespace App\Http\Controllers\Business;

use App\Helpers\calDate;


use App\Http\Controllers\Controller;
use App\Models\Member\Post;
use App\Models\Operations\Cargo;
use App\Models\Operations\Cp;
use App\Models\ShipMember\ShipPosition;
use App\Models\ShipTechnique\ShipPort;
use App\Models\Finance\ExpectedCosts;
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

//선원출근일보登记
use App\Models\Attend\AttendUser;
use App\Models\Attend\AttendType;
use App\Models\Attend\AttendTime;
use App\Models\Attend\AttendRest;
use App\Models\Attend\AttendShip;

use App\Models\ShipManage\Ship;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipMember\ShipMember;
use App\Models\Convert\VoyLog;
use App\Models\Convert\VoySettle;
use App\Models\Convert\VoySettleMain;
use App\Models\Convert\VoySettleElse;
use App\Models\Convert\VoySettleFuel;
use App\Models\Convert\VoySettleProfit;
use App\Models\ShipManage\Ctm;
use Illuminate\Support\Facades\DB;

// 일정계획
use App\Models\Schedule;
use App\Models\Decision\DecEnvironment;
use Illuminate\Support\Str;
//결재관리
use Auth;


class BusinessController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function dailyAverageCost(Request $request) {
        
        $shipList = ShipRegister::select('tb_ship_register.IMO_No', 'tb_ship_register.shipName_En', 'tb_ship_register.NickName', 'tb_ship.name')
                        ->leftJoin('tb_ship', 'tb_ship.id', '=', 'tb_ship_register.Shipid')
                        ->get();
        $shipId = $request->get('shipId');
        $costs = ExpectedCosts::where('shipNo', $shipId)->first();

        return view('business.daily_average_cost', array(
            'shipList'   => $shipList,
            'shipId'     => $shipId,
            'costs'      => $costs,
        ));
    }

    public function updateCostInfo(Request $request) {
        $shipId = $request->get('select-ship');
        $inputs = $request->get('input');

        $cost_record = ExpectedCosts::where('shipNo', $shipId)->first();
        if (empty($cost_record)) {
            $cost_record = new ExpectedCosts();
        }
        $cost_record->shipNo = $shipId;
        $cost_record->input1 = $inputs[0];
        $cost_record->input2 = $inputs[1];
        $cost_record->input3 = $inputs[2];
        $cost_record->input4 = $inputs[3];
        $cost_record->input5 = $inputs[4];
        $cost_record->input6 = $inputs[5];
        $cost_record->input7 = $inputs[6];
        $cost_record->input8 = $inputs[7];

        $cost_record->save();
        return redirect('business/dailyAverageCost?shipId='.$shipId);
    }


	public function contract(Request $request) {
        $params = $request->all();
		if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
            $firstShipInfo = ShipRegister::where('IMO_No', $shipId)->first();
            $shipName = isset($firstShipInfo->NickName) &&  $firstShipInfo->NickName != '' ? $firstShipInfo->NickName : $firstShipInfo->shipName_En;
        } else {
            $firstShipInfo = ShipRegister::first();
            if($firstShipInfo == null && $firstShipInfo == false)
                return redirect()->back();

            $shipId = $firstShipInfo->IMO_No;
            $shipName = isset($firstShipInfo->NickName) &&  $firstShipInfo->NickName != '' ? $firstShipInfo->NickName : $firstShipInfo->shipName_En;
        }

        if(isset($params['voy_id']))
            $voy_id = $params['voy_id'];
        else
            $voy_id = null;

		$shipList = ShipRegister::all();
        $cp_list = CP::where('Ship_ID', $shipId)->whereNotNull('net_profit_day')->orderBy('Voy_No', 'desc')->get();
        $tmp = CP::where('Ship_ID', $shipId)->orderBy('net_profit_day', 'desc')->first();
        if($tmp == null || $tmp == false) {
            $maxVoyNo = '';
            $maxFreight = 0;
        } else {
            $maxVoyNo = $tmp['Voy_No'];
            $maxFreight = $tmp['net_profit_day'] == null ? 0 : $tmp['net_profit_day'];
        }

        $tmp = CP::where('Ship_ID', $shipId)->whereNotNull('net_profit_day')->orderBy('net_profit_day', 'asc')->first();
        if($tmp == null || $tmp == false) {
            $minVoyNo = 0;
            $minFreight = 0;
        } else {
            $minVoyNo = $tmp['Voy_No'];
            $minFreight = $tmp['net_profit_day'] == null ? 0 : $tmp['net_profit_day'];
        }

        $status = Session::get('status');

		return view('business.ship_contract', array(
            'shipId'	    =>  $shipId,
            'shipName'	    =>  $shipName,
			'shipList'      =>  $shipList,
            'cp_list'       =>  $cp_list,
            'voy_id'        =>  $voy_id,
            'status'        =>  $status,
            
            'maxVoyNo'      => $maxVoyNo,
            'maxFreight'    => $maxFreight,
            'minVoyNo'      => $minVoyNo,
            'minFreight'    => $minFreight,
		));
    }

    public function dynRecord(Request $request) {
        $params = $request->all();
        $shipName = '';
		if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else {
            $firstShipInfo = ShipRegister::first();
            if($firstShipInfo == null && $firstShipInfo == false)
                return redirect()->back();

            $shipId = $firstShipInfo->IMO_No;
        }

        $voyId = '';
        if(isset($params['voyNo']) && isset($params['voyNo']) != '' && isset($params['voyNo']) != 0) {
            $voyId = $params['voyNo'];
        }

        $shipInfo = ShipRegister::where('IMO_No', $shipId)->first();
        if($shipInfo == null || $shipInfo == false)
            return redirect()->back();
        else {
            $shipName = $shipInfo->shipName_En;
        }

        $shipList = ShipRegister::all();
        return view('business.dynamic.record', [
            'shipList'          => $shipList,
            'shipInfo'          => $shipInfo,
            'shipId'            => $shipId,
            'shipName'          => $shipName,
            'voyId'             => $voyId,
        ]);
    }

    public function saveDynamic(Request $request) {
        $params = $request->all();
        if(!isset($params['shipId']))
            return redirect()->back();
        
        $shipId = $params['shipId'];
        $ids = $params['id'];
        $CP_ID = $params['CP_ID'];

        try {
            foreach($ids as $key => $item) {
                $voyLog = new VoyLog();
                if($item != '' && $item != null) {
                    $voyLog = VoyLog::find($item);
                }
                if($voyLog != null) {
                    $voyLog['CP_ID'] = $CP_ID;
                    $voyLog['Ship_ID'] = $shipId;
                    if(isset($params['Voy_Date'][$key]) && $params['Voy_Date'][$key] != '0000-00-00' && $params['Voy_Date'][$key] != '')
                        $voyLog['Voy_Date'] = $params['Voy_Date'][$key];
                    else
                        $voyLog['Voy_Date'] = null;

                    $voyLog['Voy_Hour'] = $params['Voy_Hour'][$key] == '' ? null : $params['Voy_Hour'][$key];
                    $voyLog['Voy_Minute'] = $params['Voy_Minute'][$key] == '' ? null : $params['Voy_Minute'][$key];
                    $voyLog['GMT'] = $params['GMT'][$key] == '' ? null : $params['GMT'][$key];
                    $voyLog['Voy_Type'] = $params['Voy_Type'][$key] == '' ? null : $params['Voy_Type'][$key];
                    $voyLog['Voy_Status'] = $params['Voy_Status'][$key] == '' ? null : $params['Voy_Status'][$key];
                    $voyLog['Ship_Position'] = $params['Ship_Position'][$key];
                    $voyLog['Cargo_Qtty'] = $params['Cargo_Qtty'][$key] == '' ? null : $params['Cargo_Qtty'][$key];
                    $voyLog['Sail_Distance'] = $params['Sail_Distance'][$key] == '' ? null : $params['Sail_Distance'][$key];
                    $voyLog['Speed'] = $params['Speed'][$key] == '' ? null : $params['Speed'][$key];
                    $voyLog['RPM'] = $params['RPM'][$key] == '' ? null : $params['RPM'][$key];
                    $voyLog['ROB_FO'] = $params['ROB_FO'][$key] == '' ? null : $params['ROB_FO'][$key];
                    $voyLog['ROB_DO'] = $params['ROB_DO'][$key] == '' ? null : $params['ROB_DO'][$key];
                    $voyLog['BUNK_FO'] = $params['BUNK_FO'][$key] == '' ? null : $params['BUNK_FO'][$key];
                    $voyLog['BUNK_DO'] = $params['BUNK_DO'][$key] == '' ? null : $params['BUNK_DO'][$key];
                    $voyLog['Remark'] = $params['Remark'][$key];

                    $voyLog->save();
                }
            }
        } catch (\Exception $exception) {
            return redirect()->back();
        }

        return redirect('/business/dynRecord?shipId=' . $shipId . '&voyNo=' . $CP_ID);
    }
    
    public function saveVoyContract(Request $request) {
        $params = $request->all();

        if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else 
            return redirect()->back();

        if (isset($params['voy_no'])) {
            $Voy_No = $params['voy_no'];
        } else 
            return redirect()->back();

        if(isset($params['voy_id'])) {
            $voy_id = $params['voy_id'];
            $cpTbl = CP::find($voy_id);
            if(empty($cpTbl)) {
                $cpTbl = new CP;
            }
        }
        else
        {
            $cpTbl = new CP;
        }

        $isExist = CP::where('Voy_No', $Voy_No)->where('Ship_Id', $shipId)->where('CP_kind', 'VOY')->first();
        if(!empty($isExist) && ($isExist['id'] != $voy_id) && $Voy_No != "") {
            return redirect()->back()->with(['status'=>'error']);
        }

        
        $cpTbl['currency'] = !isset($params['currency']) ? 1 : $params['currency'];
        $cpTbl['rate'] = $params['rate'];
        $cpTbl['CP_kind'] = $params['cp_type'];
        
        if ($params['cp_date'] == '')
            $cpTbl['CP_Date'] = null;
        else
            $cpTbl['CP_Date'] = $params['cp_date'];
        $cpTbl['Voy_No'] = $params['voy_no'];
        $cpTbl['Ship_ID'] = $shipId;
        $cpTbl['Cargo'] = $params['cargo'];
        $cpTbl['Cgo_Qtty'] = $params['qty_amount'];
        $cpTbl['Qtty_Type'] = $params['qty_type'];
        $cpTbl['LPort'] = $params['up_port'];
        $cpTbl['DPort'] = $params['down_port'];
        if ($params['lay_date'] == '')
            $cpTbl['LayCan_Date1']= null;
        else
            $cpTbl['LayCan_Date1'] = $params['lay_date'];
        if ($params['can_date'] == '')
            $cpTbl['LayCan_Date2']= null;
        else
            $cpTbl['LayCan_Date2'] = $params['can_date'];
        $cpTbl['L_Rate'] = $params['load_rate'];
        $cpTbl['D_Rate'] = $params['disch_rate'];
        $cpTbl['Freight'] = $params['freight_rate'] == '' ? 0 : $params['freight_rate'];
        $cpTbl['total_Freight'] = $params['lumpsum'];
        $cpTbl['net_profit_day'] = str_replace(',','',substr($params['net_profit_day'], 2));
        $cpTbl['deten_fee'] = $params['deten_fee'];
        $cpTbl['dispatch_fee'] = $params['dispatch_fee'];
        $cpTbl['com_fee'] = $params['com_fee'];
        $cpTbl['charterer'] = $params['charterer'] == '' ? '' : $params['charterer'];
        $cpTbl['tel_number'] = $params['tel_number'] == '' ? '' : $params['tel_number'];
        $cpTbl['Remarks'] = $params['remark'] == '' ? '' : $params['remark'];
        if ($params['file_remove'] == '1') {
            $cpTbl['is_attachment'] = 0;
            $cpTbl['attachment_url'] = null;
        }
        else if($request->hasFile('attachment')) {
            $cpTbl['is_attachment'] = 1;
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $name = date('Ymd_His') . '_VOY_' . Str::random(10). '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/contract/', $name);
            $cpTbl['attachment_url'] = url() . '/contract/' . $name;
        }

        $cpTbl['sail_time'] = _convertStr2Int($params['sail_time']);
        $cpTbl['sail_term'] = _convertStr2Int($params['sail_term']);
        $cpTbl['credit'] = _convertStr2Int($params['credit']);
        $cpTbl['speed'] = str_replace(',','',$params['speed']);
        $cpTbl['distance'] = str_replace(',','',$params['distance']);
        $cpTbl['up_ship_day'] = str_replace(',','',$params['up_ship_day']);
        $cpTbl['down_ship_day'] = str_replace(',','',$params['down_ship_day']);
        $cpTbl['wait_day'] = str_replace(',','',$params['wait_day']);
        $cpTbl['fo_sailing'] = str_replace(',','',$params['fo_sailing']);
        $cpTbl['fo_up_shipping'] = str_replace(',','',$params['fo_up_shipping']);
        $cpTbl['fo_waiting'] = str_replace(',','',$params['fo_waiting']);
        $cpTbl['fo_price'] = str_replace(',','',substr($params['fo_price'], 2));
        $cpTbl['do_sailing'] = str_replace(',','',$params['do_sailing']);
        $cpTbl['do_up_shipping'] = str_replace(',','',$params['do_up_shipping']);
        $cpTbl['do_waiting'] = str_replace(',','',$params['do_waiting']);
        $cpTbl['do_price'] = str_replace(',','',substr($params['do_price'], 2));
        $cpTbl['cargo_amount'] = str_replace(',','',$params['cargo_amount']);
        if (isset($params['freight_price'])) $cpTbl['freight_price'] = str_replace(',','',substr($params['freight_price'], 2));
        if (isset($params['batch_manage'])) $cpTbl['batch_manage'] = $params['batch_manage'] == 'on' ? 1 : 0;
        if (isset($params['batch_price'])) $cpTbl['batch_price'] = str_replace(',','',substr($params['batch_price'], 2));
        $cpTbl['fee'] = str_replace(',','',$params['fee']);
        $cpTbl['up_port_price'] = str_replace(',','',substr($params['up_port_price'], 2));
        $cpTbl['down_port_price'] = str_replace(',','',substr($params['down_port_price'], 2));
        $cpTbl['cost_per_day'] = str_replace(',','',substr($params['cost_per_day'], 2));
        $cpTbl['cost_else'] = str_replace(',','',substr($params['cost_else'], 2));
        
        $cpTbl->save();

        return redirect()->back();
        
    }

    public function saveTcContract(Request $request) {
        $params = $request->all();

        if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else 
            return redirect()->back();

        if (isset($params['voy_no'])) {
            $Voy_No = $params['voy_no'];
        } else 
            return redirect()->back();

        if(isset($params['voy_id'])) {
            $voy_id = $params['voy_id'];
            $cpTbl = CP::find($voy_id);
            if(empty($cpTbl) || ($cpTbl['id'] != $voy_id) || ($voy_id == "")) {
                $cpTbl = new CP;
            }
        }
        else
        {
            $cpTbl = new CP;
        }

        $isExist = CP::where('Voy_No', $Voy_No)->where('Ship_Id', $shipId)->where('CP_kind', 'TC')->first();
        if(!empty($isExist) && ($isExist['id'] != $voy_id) && $Voy_No != "") {
            return redirect()->back()->with(['status'=>'error']);
        }

        $cpTbl['currency'] = $params['currency'];
        $cpTbl['rate'] = $params['rate'];
        $cpTbl['CP_kind'] = $params['cp_type'];
        if ($params['cp_date'] == '')
            $cpTbl['CP_Date'] = null;
        else
            $cpTbl['CP_Date'] = $params['cp_date'];
        $cpTbl['Voy_No'] = $params['voy_no'];
        $cpTbl['Ship_ID'] = $shipId;
        $cpTbl['Cargo'] = $params['cargo'];
        $cpTbl['Cgo_Qtty'] = $params['hire_duration'];
        $cpTbl['LPort'] = $params['up_port'];
        $cpTbl['DPort'] = $params['down_port'];
        if ($params['lay_date'] == '')
            $cpTbl['LayCan_Date1']= null;
        else
            $cpTbl['LayCan_Date1'] = $params['lay_date'];
        if ($params['can_date'] == '')
            $cpTbl['LayCan_Date2']= null;
        else
            $cpTbl['LayCan_Date2'] = $params['can_date'];
        $cpTbl['L_Rate'] = $params['dely'];
        $cpTbl['D_Rate'] = $params['redely'];
        $cpTbl['Freight'] = $params['hire'] == '' ? 0 : $params['hire'];
        $cpTbl['net_profit_day'] = str_replace(',','',substr($params['net_profit_day'], 2));
        $cpTbl['total_Freight'] = $params['first_hire'];
        $cpTbl['ilohc'] = $params['ilohc'];
        $cpTbl['c_v_e'] = $params['c_v_e'];
        $cpTbl['com_fee'] = $params['com_fee'];
        $cpTbl['charterer'] = $params['charterer'];
        $cpTbl['tel_number'] = $params['tel_number'];
        $cpTbl['Remarks'] = $params['remark'];

        if ($params['tc_file_remove'] == '1') {
            $cpTbl['is_attachment'] = 0;
            $cpTbl['attachment_url'] = null;
        }
        else if($request->hasFile('attachment')) {
            $cpTbl['is_attachment'] = 1;
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $name = date('Ymd_His') . '_TC_' . Str::random(10). '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/contract/', $name);
            $cpTbl['attachment_url'] = url() . '/contract/' . $name;
        }

        $cpTbl['sail_time'] = _convertStr2Int($params['sail_time']);
        $cpTbl['sail_term'] = _convertStr2Int($params['sail_term']);
        $cpTbl['credit'] = _convertStr2Int($params['credit']);
        $cpTbl['speed'] = str_replace(',','',$params['speed']);
        $cpTbl['distance'] = str_replace(',','',$params['distance']);
        $cpTbl['up_ship_day'] = str_replace(',','',$params['up_ship_day']);
        $cpTbl['down_ship_day'] = str_replace(',','',$params['down_ship_day']);
        $cpTbl['wait_day'] = str_replace(',','',$params['wait_day']);
        $cpTbl['fo_sailing'] = str_replace(',','',$params['fo_sailing']);
        $cpTbl['fo_up_shipping'] = str_replace(',','',$params['fo_up_shipping']);
        $cpTbl['fo_waiting'] = str_replace(',','',$params['fo_waiting']);
        $cpTbl['fo_price'] = str_replace(',','',substr($params['fo_price'], 2));
        $cpTbl['do_sailing'] = str_replace(',','',$params['do_sailing']);
        $cpTbl['do_up_shipping'] = str_replace(',','',$params['do_up_shipping']);
        $cpTbl['do_waiting'] = str_replace(',','',$params['do_waiting']);
        $cpTbl['do_price'] = str_replace(',','',substr($params['do_price'], 2));
        $cpTbl['cargo_amount'] = str_replace(',','',$params['daily_rent']);
        $cpTbl['freight_price'] = str_replace(',','',substr($params['in_ilohc'], 2));
        $cpTbl['batch_price'] = str_replace(',','',substr($params['in_c_v_e'], 2));
        $cpTbl['fee'] = str_replace(',','',$params['fee']);
        //$cpTbl['up_port_price'] = str_replace(',','',substr($params['up_port_price'], 2));
        //$cpTbl['down_port_price'] = str_replace(',','',substr($params['down_port_price'], 2));
        $cpTbl['cost_per_day'] = str_replace(',','',substr($params['cost_per_day'], 2));
        $cpTbl['cost_else'] = str_replace(',','',substr($params['cost_else'], 2));

        $cpTbl->save();

        return redirect()->back();
        
    }

    public function saveNonContract(Request $request) {
        $params = $request->all();

        if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else 
            return redirect()->back();

        if (isset($params['voy_no'])) {
            $Voy_No = $params['voy_no'];
        } else 
            return redirect()->back();

        if(isset($params['voy_id'])) {
            $voy_id = $params['voy_id'];
            $cpTbl = CP::find($voy_id);
            if(empty($cpTbl) || ($cpTbl['id'] != $voy_id) || ($voy_id == "")) {
                $cpTbl = new CP;
            }
        }
        else
        {
            $cpTbl = new CP;
        }

        $isExist = CP::where('Voy_No', $Voy_No)->where('Ship_Id', $shipId)->where('CP_kind', 'TC')->first();
        if(!empty($isExist) && ($isExist['id'] != $voy_id) && $Voy_No != "") {
            return redirect()->back()->with(['status'=>'error']);
        }

        $cpTbl['currency'] = $params['currency'];
        $cpTbl['rate'] = $params['rate'];
        $cpTbl['CP_kind'] = $params['cp_type'];
        if ($params['cp_date'] == '')
            $cpTbl['CP_Date'] = null;
        else
            $cpTbl['CP_Date'] = $params['cp_date'];
        $cpTbl['Voy_No'] = $params['voy_no'];
        $cpTbl['Cgo_Qtty'] = $params['hire_duration'];
        $cpTbl['Ship_ID'] = $shipId;
        $cpTbl['LPort'] = $params['up_port'];
        $cpTbl['Remarks'] = $params['remark'];

        if ($params['non_file_remove'] == '1') {
            $cpTbl['is_attachment'] = 0;
            $cpTbl['attachment_url'] = null;
        }
        else if($request->hasFile('attachment')) {
            $cpTbl['is_attachment'] = 1;
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $name = date('Ymd_His') . '_NON_' . Str::random(10). '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/contract/', $name);
            $cpTbl['attachment_url'] = url() . '/contract/' . $name;
        }

        $cpTbl['sail_time'] = _convertStr2Int($params['sail_time']);
        $cpTbl['sail_term'] = _convertStr2Int($params['sail_term']);
        $cpTbl['credit'] = _convertStr2Int($params['credit']);
        $cpTbl['speed'] = _convertStr2Int($params['speed']);
        $cpTbl['distance'] = _convertStr2Int($params['distance']);

        $cpTbl['wait_day'] = _convertStr2Int($params['wait_day']);
        $cpTbl['fo_sailing'] = _convertStr2Int($params['fo_sailing']);
        $cpTbl['fo_up_shipping'] = _convertStr2Int($params['fo_up_shipping']);
        $cpTbl['fo_waiting'] = _convertStr2Int($params['fo_waiting']);
        $cpTbl['fo_price'] = _convertStr2Int($params['fo_price']);
        $cpTbl['do_sailing'] = _convertStr2Int($params['do_sailing']);
        $cpTbl['do_up_shipping'] = _convertStr2Int($params['do_up_shipping']);
        $cpTbl['do_waiting'] = _convertStr2Int($params['do_waiting']);
        $cpTbl['do_price'] = _convertStr2Int($params['do_price']);

        $cpTbl['cost_per_day'] = _convertStr2Int($params['cost_per_day']);
        $cpTbl['cost_else'] = _convertStr2Int($params['cost_else'], 2);

        $cpTbl->save();

        return redirect()->back();
        
    }

    public function saveCargoList(Request $request) {
        $params = $request->all();
        $cargo_ids = $params['id'];
		foreach($cargo_ids as $key => $item) {
			$cargoTbl = new Cargo();
			if($item != '' && $item != null) {
				$cargoTbl = Cargo::find($item);
			}

			if($params['name'][$key] != "") {
				$cargoTbl['name'] = $params['name'][$key];

				$cargoTbl->save();
			}
		}

        return Cargo::all();
    }

    public function savePortList(Request $request) {
        $params = $request->all();
        $port_ids = $params['id'];
		foreach($port_ids as $key => $item) {
			$portTbl = new ShipPort();
			if($item != '' && $item != null) {
				$portTbl = ShipPort::find($item);
			}

			if($params['Port_Cn'][$key] != "" || $params['Port_En'][$key] != "") {
				$portTbl['Port_Cn'] = $params['Port_Cn'][$key];
                $portTbl['Port_En'] = $params['Port_En'][$key];
				$portTbl->save();
			}
		}

        return ShipPort::all();
    }

    public function saveCtmList(Request $request) {
        $params = $request->all();

        if(isset($params['id']))
            $ids = $params['id'];
        else
            return redirect()->back();
        
        if(isset($params['shipId']))
            $shipId = $params['shipId'];

        if(isset($params['ctm_type']))
            $ctm_type = $params['ctm_type'];

        if(isset($params['activeYear']))
            $activeYear = $params['activeYear'];

        
        $seperator_symbol = g_enum('CurrencyLabel')[$ctm_type];

        foreach($ids as $key => $item) {
            $tbl = new Ctm();
            if($item != '' && $item != null)
                $tbl = Ctm::find($item);

            $reg_date = $params['reg_date'][$key];
            if(isset($reg_date) && $reg_date != '' && $reg_date != ZERO_DATE)
                $tbl['reg_date'] = $reg_date;
            else
                $tbl['reg_date'] = null;
            
            $tbl['shipId'] = $shipId;
            $tbl['voy_no'] = $params['voy_no'][$key];
            $tbl['ctm_no'] = $params['ctm_no'][$key];
            $tbl['ctm_type'] = $ctm_type;
            $tbl['profit_type'] = $params['profit_type'][$key];
            $tbl['abstract'] = $params['abstract'][$key];
            $tbl['credit'] = _convertStr2Int(str_replace($seperator_symbol, '', $params['credit'][$key]));
            $tbl['debit'] = _convertStr2Int(str_replace($seperator_symbol, '', $params['debit'][$key]));
            if($ctm_type == 'CNY')
                $tbl['ex_debit'] = $params['usd_debit'][$key];
            else
                $tbl['ex_debit'] = $params['cny_debit'][$key];

            $tbl['balance'] = _convertStr2Int(str_replace($seperator_symbol, '', $params['balance'][$key]));
            $tbl['rate'] = $params['rate'][$key];
            $tbl['remark'] = $params['remark'][$key];

            // Attachment Upload
		    if($params['is_update'][$key] == IS_FILE_UPDATE) {
			    if($request->hasFile('attachment')) {
			    	$file = $request->file('attachment')[$key];
				    $fileName = $file->getClientOriginalName();
				    $name = date('Ymd_His') . '_' . Str::random(10). '.' . $file->getClientOriginalExtension();
				    $file->move(public_path() . '/ctm/', $name);
					if($tbl['attachment'] != '' && $tbl['attachment'] != null) {
						if(file_exists($tbl['attachment']))
							@unlink($tbl['attachment']);
					}

				    $tbl['attachment'] = public_path('/ctm/') . $name;
				    $tbl['attachment_link'] = url() . '/ctm/' . $name;
				    $tbl['file_name'] = $fileName;
			    }
            }
            
            $tbl->save();
            
        }
        
        return redirect('/business/ctm?shipId=' . $shipId . '&year=' . $activeYear . '&type=' . $ctm_type);
    }

    public function settleMent(Request $request) {
        $params = $request->all();

        $shipList = ShipRegister::all();

        if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else {
            if(count($shipList) > 0)
                $shipId = $shipList[0]->IMO_No;
            else
                return redirect()->back();
        }

        $shipInfo = ShipRegister::where('IMO_No', $shipId)->first();
        if($shipInfo == null)
            $shipName = '';
        else
            $shipName = $shipInfo->Nick_Name != '' ? $shipInfo->Nick_Name : $shipInfo->shipName_En;

        $cpList = CP::where('Ship_ID', $shipId)->orderBy('Voy_No', 'desc')->get();
        if(isset($params['voyId'])) {
            $voyId = $params['voyId'];
        } else {
            if(count($cpList) > 0)
                $voyId = $cpList[0]->Voy_No;
            else
                $voyId = 0;
        }

        return view('business.settle.index', [
            'shipList'          => $shipList,
            'shipId'            => $shipId,
            'shipInfo'          => $shipInfo,
            'shipName'          => $shipName,

            'cpList'            => $cpList,
            'voyId'             => $voyId
        ]);
    }

    public function ctm(Request $request) {
        $shipRegList = ShipRegister::all();

        $params = $request->all();
        $shipId = $request->get('shipId'); 
	    $shipNameInfo = null;
        if(isset($shipId)) {
	        $shipNameInfo = ShipRegister::where('IMO_No', $shipId)->first();
        } else {
	        $shipNameInfo = ShipRegister::first();
	        $shipId = $shipNameInfo['IMO_No'];
        }

        $ctmTbl = new Ctm();
        $yearList = $ctmTbl->getYearList($shipId);

        if(isset($params['year']) && $params['year'] != '')
            $activeYear = $params['year'];
        else {
            $activeYear = $yearList[0];
        }

        if(isset($params['type']) && $params['type'] != '')
            $type = $params['type'];
        else {
            $type = 'CNY';
        }        

        return view('business.ctm.index', [
        	    'shipList'      =>  $shipRegList,
                'shipName'      =>  $shipNameInfo,
                'shipId'        =>  $shipId,
                'yearList'      =>  $yearList,

                'activeYear'    =>  $activeYear,
                'type'          =>  $type,
            ]);
    }

    public function saveVoySettle(Request $request) {
        $params = $request->all();
        if(!isset($params['shipId']))
            return redirect()->back();
        
        $shipId = $params['shipId'];
        $voyId = $params['voyId'];

        if(isset($params['main_id']) && $params['main_id'] != '')
            $settleMain = VoySettleMain::find($params['main_id']);
        else
            $settleMain = new VoySettleMain();
        
        $settleMain['shipId']                   = $shipId;
        $settleMain['voyId']                    = $voyId;
        $settleMain['load_date']                = $params['load_date'] . ' ' . $params['load_hour'] . ':' . $params['load_minute'] . ':00';
        $settleMain['dis_date']                 = $params['dis_date'] . ' ' . $params['dis_hour'] . ':' . $params['dis_minute'] . ':00';
        $settleMain['total_sail_time']          = _convertStr2Int($params['total_sail_time']);
        $settleMain['sail_time']                = _convertStr2Int($params['sail_time']);
        $settleMain['load_time']                = _convertStr2Int($params['load_time']);
        $settleMain['cargo_name']               = $params['cargo_name'];
        $settleMain['voy_type']                 = $params['voy_type'];
        $settleMain['cgo_qty']                  = _convertStr2Int($params['cgo_qty']);
        $settleMain['freight_price']            = _convertStr2Int($params['freight_price']);
        $settleMain['freight']                  = _convertStr2Int($params['freight']);
        $settleMain['total_distance']           = _convertStr2Int($params['total_distance']);
        $settleMain['lport']                    = $params['lport'];
        $settleMain['dport']                    = $params['dport'];
        $settleMain['avg_speed']                = _convertStr2Int($params['avg_speed']);
        $settleMain['com_fee']                  = _convertStr2Int($params['com_fee']);
        $settleMain->save();

        if(isset($params['origin_id']) && $params['origin_id'] != '') {
            $settleElse = VoySettleElse::find($params['origin_id']);
        } else {
            $settleElse = new VoySettleElse();
        }

        $settleElse['shipId'] = $shipId;
        $settleElse['voyId'] = $voyId;
        $settleElse['position'] = $params['origin_position'];
        if(isset($params['origin_date']) && $params['origin_date'] != '' && $params['origin_date'] != EMPTY_DATE)
            $settleElse['load_date'] = $params['origin_date'] . ' ' . $params['origin_hour'] . ':' . $params['origin_minute'] . ':00';
        else
            $settleElse['load_date'] = null;

        $settleElse['rob_fo'] = _convertStr2Int($params['origin_fo']);
        $settleElse['rob_do'] = _convertStr2Int($params['origin_do']);
        $settleElse['type'] = VOY_SETTLE_ORIGIN;

        $settleElse->save();

        
        $loadIds = $params['load_id'];
        foreach($loadIds as $key => $id) {
            if(isset($id) && $id != '') {
                $settleElse = VoySettleElse::find($id);
            } else {
                $settleElse = new VoySettleElse();
            }

            if(isset($params['load_arrival_hour'][$key]) && $params['load_arrival_hour'][$key] != '')
                $params['load_arrival_hour'][$key] = $params['load_arrival_hour'][$key];
            else
                $params['load_arrival_hour'][$key] = '00';

            if(isset($params['load_arrival_minute'][$key]) && $params['load_arrival_minute'][$key] != '')
                $params['load_arrival_minute'][$key] = $params['load_arrival_minute'][$key];
            else
                $params['load_arrival_minute'][$key] = '00';

            $settleElse['shipId'] = $shipId;
            $settleElse['voyId'] = $voyId;
            $settleElse['position'] = $params['load_position'][$key];
            if(isset($params['load_arrival_date'][$key]) && $params['load_arrival_date'][$key] != '' && $params['load_arrival_date'][$key] != EMPTY_DATE)
                $settleElse['arrival_date'] = $params['load_arrival_date'][$key] . ' ' . $params['load_arrival_hour'][$key] . ':' . $params['load_arrival_minute'][$key] . ':00';
            else
                $settleElse['arrival_date'] = null;

            if(isset($params['load_depart_date'][$key]) && $params['load_depart_date'][$key] != '' && $params['load_depart_date'][$key] != EMPTY_DATE)
                $settleElse['load_date'] = $params['load_depart_date'][$key] . ' ' . $params['load_depart_hour'][$key] . ':' . $params['load_depart_minute'][$key] . ':00';
            else
                $settleElse['load_date'] = null;

            $settleElse['rob_fo'] = _convertStr2Int($params['load_fo'][$key]);
            $settleElse['rob_do'] = _convertStr2Int($params['load_do'][$key]);
            $settleElse['type'] = VOY_SETTLE_LOAD;
            $settleElse->save();
        }

        $loadIds = $params['dis_id'];
        foreach($loadIds as $key => $id) {
            if(isset($id) && $id != '') {
                $settleElse = VoySettleElse::find($id);
            } else {
                $settleElse = new VoySettleElse();
            }

            $settleElse['shipId'] = $shipId;
            $settleElse['voyId'] = $voyId;
            $settleElse['position'] = $params['dis_position'][$key];
            if(isset($params['dis_arrival_date'][$key]) && $params['dis_arrival_date'][$key] != '' && $params['dis_arrival_date'][$key] != EMPTY_DATE)
                $settleElse['arrival_date'] = $params['dis_arrival_date'][$key] . ' ' . $params['dis_arrival_hour'][$key] . ':' . $params['dis_arrival_minute'][$key] . ':00';
            else
                $settleElse['arrival_date'] = null;

            if(isset($params['dis_depart_date'][$key]) && $params['dis_depart_date'][$key] != '' && $params['dis_depart_date'][$key] != EMPTY_DATE)
                $settleElse['load_date'] = $params['dis_depart_date'][$key] . ' ' . $params['dis_depart_hour'][$key] . ':' . $params['dis_depart_minute'][$key] . ':00';
            else
                $settleElse['load_date'] = null;

            $settleElse['rob_fo'] = _convertStr2Int($params['dis_fo'][$key]);
            $settleElse['rob_do'] = _convertStr2Int($params['dis_do'][$key]);
            $settleElse['type'] = VOY_SETTLE_DIS;
            $settleElse->save();
        }

        $loadIds = $params['fuel_id'];
        
        foreach($loadIds as $key => $id) {
            if(isset($id) && $id != '') {
                $settleElse = VoySettleElse::find($id);
            } else {
                $settleElse = new VoySettleElse();
            }

            $settleElse['shipId'] = $shipId;
            $settleElse['voyId'] = $voyId;
            $settleElse['position'] = $params['fuel_position'][$key];
            if(isset($params['fuel_arrival_date'][$key]) && $params['fuel_arrival_date'][$key] != '' && $params['fuel_arrival_date'][$key] != EMPTY_DATE)
                $settleElse['arrival_date'] = $params['fuel_arrival_date'][$key] . ' ' . $params['fuel_arrival_hour'][$key] . ':' . $params['fuel_arrival_minute'][$key] . ':00';
            else
                $settleElse['arrival_date'] = null;
            
            if(isset($params['fuel_depart_date'][$key]) && $params['fuel_depart_date'][$key] != '' && $params['fuel_depart_date'][$key] != EMPTY_DATE)
                $settleElse['load_date'] = $params['fuel_depart_date'][$key] . ' ' . $params['fuel_depart_hour'][$key] . ':' . $params['fuel_depart_minute'][$key] . ':00';
            else
                $settleElse['load_date'] = null;

            $settleElse['rob_fo'] = _convertStr2Int($params['fuel_fo'][$key]);
            $settleElse['rob_do'] = _convertStr2Int($params['fuel_do'][$key]);
            $settleElse['type'] = VOY_SETTLE_FUEL;
            $settleElse->save();
        }

        $fuelCaclId = $params['fuelCalcId'];
        if(isset($fuelCaclId) && $fuelCaclId != '') {
            $settleFuel = VoySettleFuel::find($fuelCaclId);
        } else {
            $settleFuel = new VoySettleFuel();
        }

        $settleFuel['shipId'] = $shipId;
        $settleFuel['voyId'] = $voyId;
        $settleFuel['rob_fo_1'] = _convertStr2Int($params['rob_fo_1']);
        $settleFuel['rob_do_1'] = _convertStr2Int($params['rob_do_1']);
        $settleFuel['rob_fo_2'] = _convertStr2Int($params['rob_fo_2']);
        $settleFuel['rob_do_2'] = _convertStr2Int($params['rob_do_2']);

        $settleFuel['used_fo'] = _convertStr2Int($params['used_fo']);
        $settleFuel['used_do'] = _convertStr2Int($params['used_do']);
        $settleFuel['rob_fo_price_1'] = _convertStr2Int($params['rob_fo_price_1']);
        $settleFuel['rob_fo_price_2'] = _convertStr2Int($params['rob_fo_price_2']);
        $settleFuel['rob_do_price_1'] = _convertStr2Int($params['rob_do_price_1']);
        $settleFuel['rob_do_price_2'] = _convertStr2Int($params['rob_do_price_2']);

        $settleFuel['total_fo'] = _convertStr2Int($params['total_fo']);
        $settleFuel['total_do'] = _convertStr2Int($params['total_do']);
        $settleFuel['total_fo_price'] = _convertStr2Int($params['total_fo_price']);
        $settleFuel['total_do_price'] = _convertStr2Int($params['total_do_price']);
        $settleFuel['total_fo_diff'] = _convertStr2Int($params['total_fo_diff']);
        $settleFuel['total_do_diff'] = _convertStr2Int($params['total_do_diff']);
        $settleFuel['total_fo_price_diff'] = _convertStr2Int($params['total_fo_price_diff']);
        $settleFuel['total_do_price_diff'] = _convertStr2Int($params['total_do_price_diff']);
        $settleFuel->save();

        $creditIds = $params['credit_id'];
        foreach($creditIds as $key => $id) {
            if(isset($id) && $id != '') {
                $settleProfit = VoySettleProfit::find($id);
            } else {
                $settleProfit = new VoySettleProfit();
            }

            $settleProfit['shipId'] = $shipId;
            $settleProfit['voyId'] = $voyId;
            $settleProfit['name'] = $params['credit_name'][$key];
            $settleProfit['amount'] = _convertStr2Int($params['credit_amount'][$key]);
            $settleProfit['remark'] = $params['credit_remark'][$key];
            $settleProfit['type'] = REPORT_TYPE_EVIDENCE_IN;
            $settleProfit->save();
        }

        $debitIds = $params['debit_id'];
        foreach($debitIds as $key => $id) {
            if(isset($id) && $id != '') {
                $settleProfit = VoySettleProfit::find($id);
            } else {
                $settleProfit = new VoySettleProfit();
            }

            $settleProfit['shipId'] = $shipId;
            $settleProfit['voyId'] = $voyId;
            $settleProfit['name'] = $params['debit_name'][$key];
            $settleProfit['amount'] = _convertStr2Int($params['debit_amount'][$key]);
            $settleProfit['remark'] = $params['debit_remark'][$key];
            $settleProfit['type'] = REPORT_TYPE_EVIDENCE_OUT;
            $settleProfit->save();
        }

        return redirect('/business/settleMent?shipId=' . $shipId . '&voyId=' . $voyId);
    }
    





    // Ajax
    public function ajaxContractInfo(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];

        $retVal['shipInfo'] = ShipRegister::where('IMO_No', $shipId)->first();
        $retVal['portList'] = ShipPort::orderBy('Port_En', 'asc')->get();
        $retVal['cargoList'] = Cargo::orderBy('name', 'asc')->get();

        return response()->json($retVal);
    }

    public function ajaxVoyNoValid(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];
        $voyNo = $params['voyNo'];
        $id = $params['id'];

        $origInfo = CP::where('id', $id)->first();

        $ret = true;
        if($origInfo != null) {
            $origNo = $origInfo->Voy_No;
            if($origNo == $voyNo)
                return resonse()->json($ret);
        }

        $is_exist = CP::where('Ship_ID', $shipId)->where('Voy_No', $voyNo)->first();
        if($is_exist != null)
            $ret = false;
        else
            $ret = true;

        return response()->json($ret);
    }

    public function ajaxCargoDelete(Request $request) {
        $params = $request->all();

        $id = $params['id'];
        Cargo::where('id', $id)->delete();

        return response()->json(Cargo::all());
    }

    public function ajaxPortDelete(Request $request) {
        $params = $request->all();

        $id = $params['id'];
        ShipPort::where('id', $id)->delete();

        return response()->json(ShipPort::all());
    }

    public function ajaxVoyList(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];
        $activeYear = $params['year'];

        $cp_list = CP::where('Ship_ID', $shipId)->whereRaw(DB::raw('mid(CP_Date, 1, 4) like ' . $activeYear))->orderByRaw('CONVERT(Voy_No, SIGNED) desc')->get();

        return response()->json($cp_list);
    }

    public function ajaxCPList(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];

        $cp_list = CP::where('Ship_ID', $shipId)->orderByRaw('CONVERT(Voy_No, SIGNED) desc')->get();

        return response()->json($cp_list);
    }

    public function ajaxVoyDelete(Request $request) {
        $params = $request->all();
        $id = $params['id'];
        $ret = CP::where('id', $id)->delete();

        return $this->ajaxVoyList($request);
    }

    public function ajaxDynamic(Request $request) {
        $params = $request->all();
        $shipList = ShipRegister::all();

        $retVal['shipList'] = $shipList;

        return response()->json($retVal);
    }


    public function ajaxDynamicList(Request $request) {
        $params = $request->all();

        if(isset($params['shipId']) && isset($params['voyId'])) {
            $shipId = $params['shipId'];
            $voyId = $params['voyId'];
        } else {
            return redirect()->back();
        }

        $prevData = [];
        $retVal['prevData'] = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', '<',$voyId)->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->orderBy('Voy_Date', 'desc')->orderBy('Voy_Hour', 'desc')->orderBy('Voy_Minute', 'desc')->orderBy('GMT', 'desc')->first();
        $retVal['min_date'] = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', '<',$voyId)->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->orderBy('Voy_Date', 'desc')->orderBy('Voy_Hour', 'desc')->orderBy('Voy_Minute', 'desc')->orderBy('GMT', 'desc')->first();
        $retVal['currentData'] = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', $voyId)->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc')->orderBy('GMT', 'asc')->get();
        $retVal['max_date'] = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', $voyId)->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->orderBy('Voy_Date', 'desc')->orderBy('Voy_Hour', 'desc')->orderBy('Voy_Minute', 'desc')->orderBy('GMT', 'desc')->first();

        if($retVal['min_date'] == false || $retVal['min_date'] == null) {
            $retVal['min_date'] = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', $voyId)->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc')->orderBy('GMT', 'asc')->first();
            if($retVal['min_date'] == null)
                $retVal['min_date'] = false;
        } else 
            $retVal['min_date'] = $retVal['min_date'];

        if($retVal['prevData'] == null)
            $retVal['prevData'] = VoyLog::where('Ship_ID', $shipId)->where('CP_ID', $voyId)->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc')->orderBy('GMT', 'asc')->first();

        if($retVal['max_date'] == false || $retVal['max_date'] == null)
            $retVal['max_date'] = false;
        else
            $retVal['max_date'] = $retVal['max_date'];

        return response()->json($retVal);
    }

    public function ajaxDynamicMultiSearch(Request $request) {
        $params = $request->all();
        $shipids = $params['shipId'];
        //$shipids = explode(",",$params['columns'][2]['search']['value']);
        $year = $params['year'];
        $result = [];
        foreach($shipids as $shipId)
        {
            $voyTbl = VoyLog::where('Ship_ID', $shipId);
            $voyTbl2 = VoyLog::where('Ship_ID', $shipId)->where('Voy_Status', DYNAMIC_CMPLT_DISCH);
            $voyTbl3 = VoyLog::where('Ship_ID', $shipId);
            $prevData = null;

            if(isset($params['year']) && $params['year'] != 0) {
                $voyTbl->whereRaw(DB::raw('mid(Voy_Date, 1, 4) like ' . $params['year']));
                $voyTbl2->whereRaw(DB::raw('mid(Voy_Date, 1, 4) < ' . $params['year']))->orderBy('Voy_Date', 'desc');
            }
            $voyTbl->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc');
            $voyTbl2->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc');


            $retVal['currentData'] = $voyTbl->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc')->orderBy('GMT', 'asc')->get();
            $prevData = $voyTbl2->first();
            if($prevData == null)
                $prevData = $voyTbl->first();

            $retVal['prevData'] = $prevData;
            $retVal['max_date'] = $voyTbl3->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->orderBy('Voy_Date', 'desc')->orderBy('Voy_Hour', 'desc')->orderBy('Voy_Minute', 'desc')->orderBy('GMT', 'desc')->first();
            if($retVal['max_date'] == null)
                $retVal['max_date'] = false;

            $retVal['min_date'] = $prevData;
            if($retVal['min_date'] == null)
                $retVal['min_date'] = false;
                
            $retTmp = [];
            $voyArray = [];
            $tmpVoyId = 0;
            $cp_list = [];
            foreach($retVal['currentData'] as $key => $item) {
                if(!in_array($item->CP_ID, $voyArray)) {
                    $voyArray[] = $item->CP_ID;

                    $cp_list = CP::where('Ship_ID', $shipId)->where('Voy_No', $item->CP_ID)->orderBy('Voy_No', 'desc')->get();
                    foreach($cp_list as $cp_key => $cp_item) {
                        $LPort = $cp_item->LPort;
                        $LPort = explode(',', $LPort);
                        $LPort = ShipPort::whereIn('id', $LPort)->get();
                        $tmp = '';
                        foreach($LPort as $port)
                            $tmp .= $port->Port_En . ', ';
                        $cp_list[$cp_key]->LPort = substr($tmp, 0, strlen($tmp) - 2);
            
                        $DPort = $cp_item->DPort;
            
                        $DPort = $cp_item->DPort;
                        $DPort = explode(',', $DPort);
                        $DPort = ShipPort::whereIn('id', $DPort)->get();
                        $tmp = '';
                        foreach($DPort as $port)
                            $tmp .= $port->Port_En . ', ';
                        $cp_list[$cp_key]->DPort = substr($tmp, 0, strlen($tmp) - 2);
                    }
                    $retVal['cpData'][$item->CP_ID] = $cp_list[0];
                }

                $retTmp[$item->CP_ID][] = $item;
                $tmpVoyId = $item->CP_ID;
                
            }
            $retVal['currentData'] = $retTmp;
            $retVal['voyData'] = $voyArray;

            $result[$shipId] = $retVal;
        }

        return $result;
    }

    public function ajaxDynamicSearch(Request $request) {
        $params = $request->all();

        if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
        } else {
            return redirect()->back();
        }

        $voyTbl = VoyLog::where('Ship_ID', $shipId);
        $voyTbl2 = VoyLog::where('Ship_ID', $shipId)->where('Voy_Status', DYNAMIC_CMPLT_DISCH);
        $voyTbl3 = VoyLog::where('Ship_ID', $shipId);
        $prevData = null;
        if(isset($params['type']) && isset($params['type']) != '') {
            $params['year'] = substr($params['year'], 2, 2);
            if($params['type'] == 'all') {
                if(isset($params['year']) && $params['year'] != 0 && isset($params['voyId']) && $params['voyId'] == 0) {
                    $voyTbl->whereRaw(DB::raw('mid(CP_ID, 1, 2) like ' . $params['year']));
                    $voyTbl3->whereRaw(DB::raw('mid(CP_ID, 1, 2) like ' . $params['year']));
                    $voyTbl2->whereRaw(DB::raw('mid(CP_ID, 1, 2) < ' . $params['year']))->orderBy('CP_ID', 'asc');
                }
        
                if(isset($params['voyId']) && $params['voyId'] != 0) {
                    $voyTbl->where('CP_ID', $params['voyId']);
                    $voyTbl3->where('CP_ID', $params['voyId']);
                    $voyTbl2->where('CP_ID', '<', $params['voyId'])->orderBy('CP_ID', 'desc');
                } else {
                    $voyTbl->where('CP_ID', 0);
                    $voyTbl3->where('CP_ID', 0);
                    $voyTbl2->where('CP_ID', '<', 0)->orderBy('CP_ID', 'desc');
                }

            } else if($params['type'] == 'analyze') {
                if(isset($params['year']) && $params['year'] != 0) {
                    $voyTbl->whereRaw(DB::raw('mid(CP_ID, 1, 2) like ' . $params['year']));
                    $voyTbl2->whereRaw(DB::raw('mid(CP_ID, 1, 2) < ' . $params['year']))->orderBy('Voy_Date', 'desc');
                }
        
                $voyTbl->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc');
                $voyTbl2->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc');
            }
        }


        $retVal['currentData'] = $voyTbl->orderBy('Voy_Date', 'asc')->orderBy('Voy_Hour', 'asc')->orderBy('Voy_Minute', 'asc')->orderBy('GMT', 'asc')->get();
        $prevData = $voyTbl2->first();
        if($prevData == null)
            $prevData = $voyTbl->first();

        $retVal['prevData'] = $prevData;
        $retVal['max_date'] = $voyTbl3->where('Voy_Status', DYNAMIC_CMPLT_DISCH)->orderBy('Voy_Date', 'desc')->orderBy('Voy_Hour', 'desc')->orderBy('Voy_Minute', 'desc')->orderBy('GMT', 'desc')->first();
        if($retVal['max_date'] == null)
            $retVal['max_date'] = false;

        $retVal['min_date'] = $prevData;
        if($retVal['min_date'] == null)
            $retVal['min_date'] = false;
            
        if($params['type'] == 'analyze') {
            $retTmp = [];
            $voyArray = [];
            $tmpVoyId = 0;
            $cp_list = [];
            foreach($retVal['currentData'] as $key => $item) {
                if(!in_array($item->CP_ID, $voyArray)) {
                    $voyArray[] = $item->CP_ID;

                    $cp_list = CP::where('Ship_ID', $shipId)->where('Voy_No', $item->CP_ID)->orderBy('Voy_No', 'desc')->get();
                    foreach($cp_list as $cp_key => $cp_item) {
                        $LPort = $cp_item->LPort;
                        $LPort = explode(',', $LPort);
                        $LPort = ShipPort::whereIn('id', $LPort)->get();
                        $tmp = '';
                        foreach($LPort as $port)
                            $tmp .= $port->Port_En . ', ';
                        $cp_list[$cp_key]->LPort = substr($tmp, 0, strlen($tmp) - 2);
            
                        $DPort = $cp_item->DPort;
            
                        $DPort = $cp_item->DPort;
                        $DPort = explode(',', $DPort);
                        $DPort = ShipPort::whereIn('id', $DPort)->get();
                        $tmp = '';
                        foreach($DPort as $port)
                            $tmp .= $port->Port_En . ', ';
                        $cp_list[$cp_key]->DPort = substr($tmp, 0, strlen($tmp) - 2);
                    }
                    $retVal['cpData'][$item->CP_ID] = $cp_list[0];
                }

                $retTmp[$item->CP_ID][] = $item;
                $tmpVoyId = $item->CP_ID;
                
            }

            $retVal['currentData'] = $retTmp;
            $retVal['voyData'] = $voyArray;
        }

        return response()->json($retVal);
    }

    public function ajaxVoyAllList(Request $request) {
        $params = $request->all();
        $shipId = $params['shipId'];

        if(isset($params['year'])) {
            $params['year'] = substr($params['year'], 2, 2);
            $cp_list = CP::where('Ship_ID', $shipId)->whereRaw(DB::raw('mid(Voy_No, 1, 2) like ' . $params['year']))->orderBy('Voy_No', 'desc')->get();
        } else {
            $cp_list = CP::where('Ship_ID', $shipId)->orderBy('Voy_No', 'desc')->get();
        }

        foreach($cp_list as $key => $item) {
            $LPort = $item->LPort;
            $LPort = explode(',', $LPort);
            $LPort = ShipPort::whereIn('id', $LPort)->get();
            $tmp = '';
            foreach($LPort as $port)
                $tmp .= $port->Port_En . ', (' . $port->Port_Cn . ') / ';
            $cp_list[$key]->LPort = substr($tmp, 0, strlen($tmp) - 2);

            $DPort = $item->DPort;

            $DPort = $item->DPort;
            $DPort = explode(',', $DPort);
            $DPort = ShipPort::whereIn('id', $DPort)->get();
            $tmp = '';
            foreach($DPort as $port)
                $tmp .= $port->Port_En . ', (' . $port->Port_Cn . ') / ';
            $cp_list[$key]->DPort = substr($tmp, 0, strlen($tmp) - 2);
        }

        return response()->json($cp_list);
    }

    public function ajaxDeleteDynrecord(Request $request) {
        $params = $request->all();

        $id = $params['id'];
        $ret = VoyLog::where('id', $id)->delete();

        return response()->json($ret);
    }

    public function ajaxCtm(Request $request) {
        $params = $request->all();

        $tbl = Ctm::orderBy('ctm_no', 'asc');
        $prevTbl = Ctm::orderBy('reg_date', 'desc');
        if(isset($params['shipId'])) {
            $shipId = $params['shipId'];
            $tbl->where('shipId', $shipId);
            $prevTbl->where('shipId', $shipId);
        } else
            return response()->json(false);

        if(isset($params['year']) && $params['year'] != '') {
            $year = $params['year'];
            $tbl->whereRaw(DB::raw('mid(reg_date, 1, 4) like ' . $year));
            $prevTbl->whereRaw(DB::raw('mid(reg_date, 1, 4) < ' . $year));
        }

        if(isset($params['type']) && $params['type'] != '') {
            $type = $params['type'];
            $tbl->where('ctm_type', $type);
            $prevTbl->where('ctm_type', $type);
        }

        $list = $tbl->get();
        $prevList = $prevTbl->first();
        $voyList = CP::where('Ship_ID', $shipId)->get();

        $retVal['list'] = $list;
        $retVal['prevList'] = $prevList;
        if($prevList == null) {
            $retVal['prevList']['balance'] = 0;
            $retVal['prevList']['rate'] = 0;
        }
        $retVal['voyList'] = $voyList;

        return response()->json($retVal);
    }

    public function ajaxCtmDelete(Request $request) {
        $params = $request->all();

        $id = $params['id'];
        $ret = Ctm::where('id', $id)->delete();

        return response()->json($ret);
    }

    public function ajaxVoySettleIndex(Request $request) {
        $params = $request->all();

        $shipId = $params['shipId'];
        $voyId = $params['voyId'];

        $voySettleTbl = new VoySettle();
        $is_exist = VoySettleMain::where('shipId', $shipId)->where('voyId', $voyId)->first();
        
        // If don't exist saved data in voy settle table.
        if($is_exist == null) {
            $retVal = $voySettleTbl->getTotalData($shipId, $voyId);
        } else {
            $retVal = $voySettleTbl->getData($shipId, $voyId);
        }

        return response()->json($retVal);
    }

    public function ajaxVoySettleDelete(Request $request) {
        $params = $request->all();

        $id = $params['id'];

        VoySettleElse::where('id', $id)->delete();

    }
}