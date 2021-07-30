<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voy;

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
}
