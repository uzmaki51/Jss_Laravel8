<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipManage\Fuel;

class FuelController extends Controller
{
    //
    public function ajax_fuelReset(Request $request) {
        $id = $request->get('id');

        $ret = Fuel::where('id', $id)->delete();

        return response()->json($ret);
    }
}
