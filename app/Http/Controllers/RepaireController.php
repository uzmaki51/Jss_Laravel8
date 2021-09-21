<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipManage\ShipRegister;
use App\Models\ShipManage\ShipMaterialSubKind;
use App\Models\ShipManage\ShipMaterialCategory;
use App\Models\BreadCrumb;
use App\Models\Repaire;
use Auth;

class RepaireController extends Controller
{
    public function register(Request $request) {
        $url = $request->path();
        $breadCrumb = BreadCrumb::getBreadCrumb($url);

        $user_pos = Auth::user()->pos;
        if($user_pos == STAFF_LEVEL_SHAREHOLDER || $user_pos == STAFF_LEVEL_CAPTAIN)
            $shipList = ShipRegister::getShipForHolder();
        else {
            $shipList = ShipRegister::orderBy('id')->get();
        }

        $params = $request->all();

        $shipRegTbl = new ShipRegister();

        $shipName  = '';
        if(isset($params['id'])) {
            $shipId = $params['id'];
        } else {
            if(count($shipList) > 0) {
                $shipId = $shipList[0]['IMO_No'];
            } else {
                $shipId = 0;
            }
        }
        
        $shipName = $shipRegTbl->getShipNameByIMO($shipId);
        
        $yearList = [2021];

        if(isset($params['year'])) {
            $activeYear = $params['year'];
         } else {
            $activeYear = $yearList[0];
         }

        // Department List from 设备清单
        $departList = ShipMaterialCategory::all();

        // Type List from 设备清单
        $typeList = ShipMaterialSubKind::all();


        return view('repaire.register', [
            'shipList'      => $shipList,
            'shipId'        => $shipId,
            'shipName'      => $shipName,
            'years'         => $yearList,
            'activeYear'    => $activeYear,
            'activeMonth'   => intval(date('m')),
            'departList'    => $departList,
            'typeList'      => $typeList,
            'chargeList'    => [],

            'breadCrumb'    => $breadCrumb,
        ]);
    }

    public function update(Request $request) {
        $params = $request->all();
        
        $tbl = new Repaire();
        $ret = $tbl->udpateData($params);

        return redirect()->back()->with(['message'      => 'Success']);
    }

    public function ajax_list(Request $request) {
        $params = $request->all();

        if(!isset($params['ship_id'])) return false;
        // $ship_id = $params['ship_id'];
        // $year = $params['year'];
        // $month = $params['month'];
        $tbl = new Repaire();
        $list = $tbl->getList($params);

        return response()->json($list);
    }

    public function ajax_delete(Request $request) {
        $id = $request->get('id');

        $ret = Repaire::where('id', $id)->delete();

        return response()->json($ret);
    }
}
