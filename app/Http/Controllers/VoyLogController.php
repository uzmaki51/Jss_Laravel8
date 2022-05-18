<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voy;
use App\Models\ShipManage\ShipRegister;

class VoyLogController extends Controller
{
    //

    public function ajaxGetVoyData(Request $request) {
        $tbl = new Voy();

        $shipId = $request->get('shipId');
        $voyId = $request->get('voyId');
        $year = $request->get('year');

        $retVal = $tbl->getVoyInfoByYear($shipId, $year);

        return response()->json($retVal);
    }

    public function ajaxGetVoyDatas(Request $request) {
        $tbl = new Voy();

        $shipList = ShipRegister::where('RegStatus', '!=', 3)->select('IMO_NO')->orderBy('id')->get();
        $normal_ids = [];
        foreach ($shipList as $ship) $normal_ids[] = $ship->IMO_NO;
        $shipIds = $request->get('shipIds');
        $year = $request->get('year');

        $retVal = [];
        foreach($shipIds as $id) {
            if (in_array($id, $normal_ids)) {
                $retVal[$id] = $tbl->getVoyInfoByYear($id, $year);
            }
        }

        return response()->json($retVal);
    }
}
