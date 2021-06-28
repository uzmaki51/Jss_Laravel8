@extends('layout.header')
<?php
    $isHolder = Session::get('IS_HOLDER');
    $ships = Session::get('shipList');
?>
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <div class="col-md-3">
                <h4><b>船舶动态</b></h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <table class="arc-std-table table table-striped table-bordered table-hover" style="font-size: 13px">
                    <thead>
                    <tr class="black br-hblue">
                        <th class="center" rowspan="2">{{transShipOperation("movement.ShipName")}}</th>
                        <th class="center" rowspan="2">{{transShipOperation("movement.VoyNo")}}</th>
                        <th class="center" rowspan="2">{{transShipOperation("movement.Voy_Date")}}</th>
                        <th class="center" rowspan="2">{{transShipOperation("movement.Status_Name")}}</th>
                        <th class="center" rowspan="2">{{transShipOperation("movement.Ship_Position")}}</th>
                        <th class="center" rowspan="2">{{transShipOperation("movement.Cargo_Qtty")}}</th>
                        <th class="center" rowspan="2"><?php print_r(transShipOperation("movement.Sail_Distance"));?></th>
                        <th class="center" colspan="5">{{transShipOperation("movement.Balance")}}</th>
                        <th class="center" style="width: 100px;" rowspan="2">{{transShipOperation("movement.Remarks")}}</th>
                    </tr>
                    <tr class="black br-hblue">
                        <th class="center"><?php print_r(transShipOperation("movement.ROB_FO"));?></th>
                        <th class="center"><?php print_r(transShipOperation("movement.ROB_DO"));?></th>
                        <th class="center"><?php print_r(transShipOperation("movement.ROB_LO_M"));?></th>
                        <th class="center"><?php print_r(transShipOperation("movement.ROB_LO_A"));?></th>
                        <th class="center"><?php print_r(transShipOperation("movement.ROB_FW"));?></th>
                    </tr>
                    </thead>
                    <tbody id="log-table">
                    @foreach($movement as $list)
                        @if(!$isHolder)
                        <tr>
                            <td><a href="{{ url('operation/movement?shipId='.$list->RegNo) }}">{{ $list->shipName_Cn }}</a></td>
                            <td class="center">{{ $list->Voy_No }}</td>
                            <td data-id="{{$list->id}}" class="center" wrap>
                                {!! convert_datetime_origin($list->Voy_Date) !!}
                            </td>
                            <td class="center" data-id="{{$list->Voy_Status}}">{{ $list->Voy_St }}</td>
                            <td class="center">{{ $list->Ship_Position }}</td>
                            <td class="center">@if($list->Cargo_Qtty) {{number_format($list->Cargo_Qtty, 0, '.', ',')}} @endif</td>
                            <td class="center">@if($list->Sail_Distance) {{number_format($list->Sail_Distance, 0, '.', ',')}} @endif</td>
                            <td class="center" style="word-break: break-all;">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_FO) }}</td>
                            <td class="center" style="word-break: break-all;">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_DO) }}</td>
                            <td class="center" style="word-break: break-all;">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_LO_M) }}</td>
                            <td class="center">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_LO_A) }}</td>
                            <td class="center">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_FW) }}</td>
                            <td style="width:100px"><a class="simple_text hide-option" style="width:100px;padding-top:4px;cursor: pointer" title="{{ $list->Remarks }}">{{ $list->Remarks }}</a></td>
                        </tr>
                        @elseif($isHolder && in_array($list->id, $ships))
                            <tr>
                                <td><a href="{{ url('operation/movement?shipId='.$list->RegNo) }}">{{ $list->shipName_Cn }}</a></td>
                                <td class="center">{{ $list->Voy_No }}</td>
                                <td data-id="{{$list->id}}" class="center" wrap>
                                    {!! convert_datetime_origin($list->Voy_Date) !!}
                                </td>
                                <td class="center" data-id="{{$list->Voy_Status}}">{{ $list->Voy_St }}</td>
                                <td class="center">{{ $list->Ship_Position }}</td>
                                <td class="center">@if($list->Cargo_Qtty) {{number_format($list->Cargo_Qtty, 0, '.', ',')}} @endif</td>
                                <td class="center">@if($list->Sail_Distance) {{number_format($list->Sail_Distance, 0, '.', ',')}} @endif</td>
                                <td class="center" style="word-break: break-all;">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_FO) }}</td>
                                <td class="center" style="word-break: break-all;">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_DO) }}</td>
                                <td class="center" style="word-break: break-all;">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_LO_M) }}</td>
                                <td class="center">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_LO_A) }}</td>
                                <td class="center">{{ \App\Http\Controllers\Util::getNumberFt($list->ROB_FW) }}</td>
                                <td style="width:100px"><a class="simple_text hide-option" style="width:100px;padding-top:4px;cursor: pointer" title="{{ $list->Remarks }}">{{ $list->Remarks }}</a></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection