<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/13
 * Time: 9:39
 */

namespace App\Http\Controllers\Dynamic;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Member\Unit;

use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DynamicController extends Controller
{
    protected $userinfo;

    public function __construct() {
        $this->middleware('auth');
    }

    public function ajaxGetDynamicData(Request $request) {
        $params = $request->all();
		$type = $params['type'];
		if ($type == 'nationality') {
			$result = DB::table('tb_dynamic_nationality')->select('*')->get();
			return response()->json($result);
		}
		else if ($type == 'rank') {
			$result = DB::table('tb_ship_duty')->select('*')->orderByRaw('CAST(OrderNo AS SIGNED) ASC')->get();			
			return response()->json($result);
		}
		else if ($type == 'capacity') {
			$result = DB::table('tb_member_capacity')->select('*')->get();
			return response()->json($result);
		}
		else if ($type == 'shiptype') {
			$result = DB::table('tb_ship_type')->select('*')->get();
			return response()->json($result);
		}
		else if ($type == 'port') {
			$result = DB::table('tbl_port')->select('*')->orderBy('Port_En')->get();
			return response()->json($result);
		}
		else if ($type == 'pos') {
			$result = DB::table('tb_pos')->select('*')->orderByRaw('CAST(orderNum AS SIGNED) ASC')->get();			
			return response()->json($result);
		}

		return response()->json('fail');
    }

	public function ajaxSetDynamicData(Request $request) {
		$params = $request->all();
		$type = $params['type'];

		if ($type == 'nationality') {
			$list = $params['list'];
			$default = $params['default'];

			$result = DB::table('tb_dynamic_nationality')->truncate();
			$i = 0;
			foreach($list as $item) {
				if ($item != '')
				{
					if ($i == $default)
						DB::table('tb_dynamic_nationality')->insert(['name' => $item, 'isDefault' => '1']);
					else
						DB::table('tb_dynamic_nationality')->insert(['name' => $item]);
					$i ++;
				}
			}
		}
		else if($type == 'shiptype') {
			$orderno = $params['orderno'];
			$name = $params['name'];

			$result = DB::table('tb_ship_type')->truncate();
			for ($i=0;$i<count($orderno);$i++)
			{
				if (/*$orderno[$i] != '' || */$name[$i] != '') {
					DB::table('tb_ship_type')->insert(['OrderNo' => $orderno[$i], 'ShipType' => $name[$i]]);
				}
			}
		}
		else if($type == 'rank') {
			$ids = $params['id'];
			$orderno = $params['orderno'];
			$name = $params['name'];
			$abb = $params['abb'];
			$description = $params['description'];

			//$result = DB::table('tb_ship_duty')->truncate();
			DB::table('tb_ship_duty')->whereNotIn('id', $ids)->delete();
			for ($i=0;$i<count($orderno);$i++)
			{
				if (/*$orderno[$i] != '' || */$abb[$i] != '' || $name[$i] != '' || $description[$i] != '') {
					if ($ids[$i] == '')
						DB::table('tb_ship_duty')->insert(['OrderNo' => $orderno[$i], 'Abb' => $abb[$i], 'Duty_En' => $name[$i], 'Description' => $description[$i]]);
					else
						DB::table('tb_ship_duty')->where('id', $ids[$i])->update(['OrderNo' => $orderno[$i], 'Abb' => $abb[$i], 'Duty_En' => $name[$i], 'Description' => $description[$i]]);
				}
			}
		}
		else if($type == 'capacity') {
			$name = $params['name'];
			$stcw = $params['STCW'];
			$description = $params['description'];

			$result = DB::table('tb_member_capacity')->truncate();
			for ($i=0;$i<count($name);$i++)
			{
				if ($stcw[$i] != '' || $name[$i] != '' || $description[$i] != '') {
					DB::table('tb_member_capacity')->insert(['Capacity_En' => $name[$i], 'STCWRegID' => $stcw[$i], 'Remarks' => $description[$i]]);
				}
			}
		}
		else if($type == 'port') {
			$Port_En = $params['port_en'];
			$Port_Cn = $params['port_cn'];

			$result = DB::table('tbl_port')->truncate();
			for ($i=0;$i<count($Port_En);$i++)
			{
				if ($Port_En[$i] != '' || $Port_Cn[$i] != '') {
					DB::table('tbl_port')->insert(['Port_En' => $Port_En[$i], 'Port_Cn' => $Port_Cn[$i]]);
				}
			}
		}
		else if($type == 'pos') {
			$ids = $params['id'];
			$Pos_OrderNum = $params['orderno'];
			$Pos_Title = $params['name'];

			//$result = DB::table('tbl_port')->truncate();
			DB::table('tb_pos')->whereNotIn('id', $ids)->delete();
			for ($i=0;$i<count($Pos_OrderNum);$i++)
			{
				if ($Pos_Title[$i] != '') {
					if ($ids[$i] == '')
						DB::table('tb_pos')->insert(['orderNum' => $Pos_OrderNum[$i], 'title' => $Pos_Title[$i]]);
					else
						DB::table('tb_pos')->where('id', $ids[$i])->update(['orderNum' => $Pos_OrderNum[$i], 'title' => $Pos_Title[$i]]);
				}
			}
		}
		else
		{
			return response()->json('-1');
		}

		return response()->json('0');
	}

    public function ajaxSetNationality(Request $request) {
        $params = $request->all();
        
    }

    public function ajaxGetRank(Request $request) {

    }

    public function ajaxSetRank(Request $request) {

    }


	public function ajaxReportDetail(Request $request) {
		$params = $request->all();
		$userid = Auth::user()->id;
		$userRole = Auth::user()->isAdmin;

		Session::forget('reportFiles');
//		if($userRole != SUPER_ADMIN)
//			return response()->json('-1');

		$decideTbl = new DecisionReport();
		$retVal = $decideTbl->getReportDetail($params);

		return response()->json($retVal);
	}

	public function ajaxReportData(Request $request) {
    	$params = $request->all();

    	$shipList = ShipRegister::getShipListByOrigin();

    	if(isset($params['shipId'])) {
    		$shipRegNo = ShipRegister::find($params['shipId'])['RegNo'];
    		$voyList = VoyLog::where('ship_ID', $shipRegNo)->get();
	    } else {
			$voyList = array();
	    }

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

		$hasFile = $request->file('file');
		if(isset($hasFile)) {
			if(isset($hasFile)) {
				$name = date('Ymd_H_i_s'). '.' . $hasFile->getClientOriginalExtension();
				$hasFile->move(public_path() . '/files/', $name);
				$fileList[] =  array($hasFile->getClientOriginalName(), '/files/' . $name);

				if(Session::get('reportFiles')) {
					$reportFile = Session::get('reportFiles');
				} else {
					$reportFile = array();
				}
				$reportFile[] = $fileList;
				Session::put('reportFiles', $reportFile);
			}
		}

		$retVal = Session::get('reportFiles');

		$retVal[][] = array('请选择文件。', '请选择文件。');

		return response()->json($retVal);
	}

	public function ajaxGetDepartment() {
		$retVal = Unit::where('parentId', '!=', 0)->get();

		return response()->json($retVal);
	}
}