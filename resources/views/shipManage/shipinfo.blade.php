<?php
if(isset($is_excel))
	$header = 'excel-header';
else
	$header = 'header';

$isShareHolder = Auth::user()->isAdmin == IS_SHAREHOLDER ? true : false;
$shipList = explode(',', Auth::user()->shipList);
?>
@extends('layout.' . $header)

@section('scripts')
    <script>
        $('#ship_list').on('change', function(e) {
            location.href = '/shipManage/shipinfo?id=' + $(this).val();
        });
    </script>
@endsection

@section('content')
    @if(!isset($is_excel))
        <div class="main-content">
            <div class="page-content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <h4><b>Ship List</b></h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-1">
                        <select class="form-control" id="ship_list">
                            @foreach($list as $key => $item)
                                <option value="{{ $item->id }}" {{ isset($id) && $id == $item->id ? 'selected' : '' }}>{{ empty($item->NickName) ? $item->shipName_En : $item->NickName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5">
                        <div class="btn-group f-right">
                            <a href="exportShipInfo?id={{ $id }}" class="btn btn-warning btn-sm" id="excel-general">
                                <i class="icon-table"></i>{{ trans('common.label.excel') }}
                            </a>
                            <a href="javascript: shipInfoExcel()" class="btn btn-warning btn-sm d-none" id="excel-formA">
                                <i class="icon-table"></i>{{ trans('common.label.excel') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6"></div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <ul class="nav nav-tabs ship-register">
                            <li class="active" >
                                <a data-toggle="tab" href="#general" onclick="changeTab('general')">
                                    规范
                                </a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#form_a" onclick="changeTab('formA')">
                                    FORM A
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="general" class="tab-pane active">
    @else
        @include('layout.excel-style')
    @endif
                                <table class="table table-bordered excel-output" id="excel-output">
                                    <thead>
                                    <tr>
                                        <th class="title" colspan="2" style="font-size: 16px;">SHIP PARTICULARS</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP NAME</td>
                                        <td>@if(isset($shipInfo['shipName_En'])){{$shipInfo['shipName_En']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td  style="background-color: #f8f8f8;" class="font-bold">IMO NO</td>
                                        <td>@if(isset($shipInfo['IMO_No'])){{$shipInfo['IMO_No']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CLASS</td>
                                        <td>@if(isset($shipInfo['Class'])){{$shipInfo['Class']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CALL SIGN</td>
                                        <td>@if(isset($shipInfo['CallSign'])){{$shipInfo['CallSign']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MMSI NO</td>
                                        <td>@if(isset($shipInfo['MMSI'])){{$shipInfo['MMSI']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">INMARSAT Number (1/2)</td>
                                        <td>@if(isset($shipInfo['INMARSAT'])){{$shipInfo['INMARSAT']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">ORIGINAL NAME</td>
                                        <td>@if(isset($shipInfo['OriginalShipName'])){{$shipInfo['OriginalShipName']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FORMER NAME</td>
                                        <td>@if(isset($shipInfo['FormerShipName'])){{$shipInfo['FormerShipName']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FLAG</td>
                                        <td>@if(isset($shipInfo['Flag'])){{$shipInfo['Flag']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">REGISTRY PORT</td>
                                        <td>@if(isset($shipInfo['PortOfRegistry'])){{$shipInfo['PortOfRegistry']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">OWNER</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['Owner_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">ISM COMPANY</td>
                                        <td>@if(isset($shipInfo['ISM_Cn'])){{$shipInfo['ISM_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP TYPE</td>
                                        <td>@if(isset($shipInfo['ShipType'])){{$shipInfo['ShipType']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP BUILDER</td>
                                        <td>@if(isset($shipInfo['ShipBuilder'])){{$shipInfo['ShipBuilder']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BUILD DATE/PLACE</td>
                                        <td>@if(isset($shipInfo['BuildPlace_Cn'])){{$shipInfo['BuildPlace_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">GT</td>
                                        <td>@if(isset($shipInfo['GrossTon'])){{$shipInfo['GrossTon']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">NT</td>
                                        <td>@if(isset($shipInfo['NetTon'])){{$shipInfo['NetTon']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">DWT</td>
                                        <td>@if(isset($shipInfo['Deadweight'])){{$shipInfo['Deadweight']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">LDT</td>
                                        <td>@if(isset($shipInfo['Displacement'])){{$shipInfo['Displacement']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">LOA</td>
                                        <td>@if(isset($shipInfo['LOA'])){{$shipInfo['LOA']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BM</td>
                                        <td>@if(isset($shipInfo['BM'])){{$shipInfo['BM']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">DM</td>
                                        <td>@if(isset($shipInfo['DM'])){{$shipInfo['DM']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SUMMER DRAFT</td>
                                        <td>@if(isset($shipInfo['Draught'])){{$shipInfo['Draught']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">TPC</td>
                                        <td>@if(isset($shipInfo['DeckErection_F'])){{$shipInfo['DeckErection_F']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FW_Allowance</td>
                                        <td>{{ isset($freeBoard['new_free_fw']) ? $freeBoard['new_free_fw'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">M/E NO_TYPE</td>
                                        <td>{{ isset($shipInfo['No_TypeOfEngine']) ? $shipInfo['No_TypeOfEngine'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">POWER</td>
                                        <td>@if(isset($shipInfo['Power'])){{$shipInfo['Power']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">RPM</td>
                                        <td>@if(isset($shipInfo['rpm'])){{$shipInfo['rpm']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MADE YEAR</td>
                                        <td>@if(isset($shipInfo['EngineDate'])){{$shipInfo['EngineDate']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SERVICE SPEED (Kn)</td>
                                        <td>@if(isset($shipInfo['Speed'])){{$shipInfo['Speed']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">PROPELLER DIA/PITCH...?</td>
                                        <td>@if(isset($shipInfo['Speed'])){{$shipInfo['Speed']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">GENERATOR SET</td>
                                        <td>{{ isset($shipInfo['PrimeMover']) ? $shipInfo['PrimeMover'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">OUTPUT</td>
                                        <td>{{ isset($shipInfo['GeneratorOutput']) ? $shipInfo['GeneratorOutput'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BOILER NO_TYPE</td>
                                        <td>@if(isset($shipInfo['Boiler'])){{$shipInfo['Boiler']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BOILER MAKER</td>
                                        <td>@if(isset($shipInfo['BoilerManufacturer'])){{$shipInfo['BoilerManufacturer']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">PRESSURE</td>
                                        <td>{{ isset($shipInfo['BoilerPressure']) ? $shipInfo['BoilerPressure'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FO CONSUMPTION (mt/day)</td>
                                        <td>@if(isset($shipInfo['FOSailCons_S'])){{$shipInfo['FOSailCons_S']}}@endif/@if(isset($shipInfo['FOL/DCons_S'])){{$shipInfo['FOL/DCons_S']}}@endif/@if(isset($shipInfo['FOIdleCons_S'])){{$shipInfo['FOIdleCons_S']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MDO CONSUMPTION (mt/day)</td>
                                        <td>@if(isset($shipInfo['DOSailCons_S'])){{$shipInfo['DOSailCons_S']}}@endif/@if(isset($shipInfo['DOL/DCons_S'])){{$shipInfo['DOL/DCons_S']}}@endif/@if(isset($shipInfo['DOIdleCons_S'])){{$shipInfo['DOIdleCons_S']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FO/DO TK CAPACITY (㎥)</td>
                                        <td>@if(isset($shipInfo['FuelBunker'])){{$shipInfo['FuelBunker']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BALLAST TK CAPACITY (㎥)</td>
                                        <td>@if(isset($shipInfo['Ballast'])){{$shipInfo['Ballast']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLDS/HATCHES NO</td>
                                        <td>@if(isset($shipInfo['NumberOfHolds'])){{$shipInfo['NumberOfHolds']}}@endif / @if(isset($shipInfo['NumberOfHatchways'])){{$shipInfo['NumberOfHatchways']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLD CAPACITY(G/B)㎥</td>
                                        <td>@if(isset($shipInfo['CapacityOfHoldsG'])){{$shipInfo['CapacityOfHoldsG']}}@endif / @if(isset($shipInfo['CapacityOfHoldsB'])){{$shipInfo['CapacityOfHoldsB']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HATCH COVER SIZE/TYPE</td>
                                        <td>{{ isset($shipInfo['SizeOfHatchways']) ? $shipInfo['SizeOfHatchways'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HOLD SIZE</td>
                                        <td>@if(isset($shipInfo['HoldsDetail'])){{$shipInfo['HoldsDetail']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CARGO GEAR</td>
                                        <td>@if(isset($shipInfo['LiftingDevice'])){{$shipInfo['LiftingDevice']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HEIGHT FM KEEL TO MAST</td>
                                        <td>@if(isset($shipInfo['DeckErection_H'])){{$shipInfo['DeckErection_H']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">MAX PERMISSBLE LOAD(TANK TOP/ON DECK/HATCH COVER)</td>
                                        <td>@if(isset($shipInfo['TK_TOP'])){{ $shipInfo['TK_TOP'] . '/' }}@endif  @if(isset($shipInfo['ON_DECK'])){{ $shipInfo['ON_DECK'] . '/' }}@endif @if(isset($shipInfo['H_COVER'])){{$shipInfo['H_COVER']}}@endif</td>
                                    </tr>
                                    </tbody>
                                </table>
                                @if(!isset($is_excel))
                            </div>
                            <div id="form_a" class="tab-pane">
                                <table class="table table-bordered excel-output" id="formA-table">
                                    <thead>
                                    <tr>
                                        <th class="title" colspan="2" style="font-size: 16px;">SHIP PARTICULARS (A)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP NAME</td>
                                        <td>@if(isset($shipInfo['shipName_En'])){{$shipInfo['shipName_En']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FLAG</td>
                                        <td>@if(isset($shipInfo['Flag'])){{$shipInfo['Flag']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BUILD DATE/PLACE</td>
                                        <td>@if(isset($shipInfo['BuildPlace_Cn'])){{$shipInfo['BuildPlace_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SHIP TYPE</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['ShipType']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td  style="background-color: #f8f8f8;">IMO NO</td>
                                        <td>@if(isset($shipInfo['IMO_No'])){{$shipInfo['IMO_No']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">GT</td>
                                        <td>@if(isset($shipInfo['GrossTon'])){{$shipInfo['GrossTon']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">NT</td>
                                        <td>@if(isset($shipInfo['NetTon'])){{$shipInfo['NetTon']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8; font-weight: bold;">DWT</td>
                                        <td>@if(isset($shipInfo['Deadweight'])){{$shipInfo['Deadweight']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">CALL SIGN</td>
                                        <td>@if(isset($shipInfo['CallSign'])){{$shipInfo['CallSign']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">LOA</td>
                                        <td>@if(isset($shipInfo['LOA'])){{$shipInfo['LOA']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">BM</td>
                                        <td>@if(isset($shipInfo['BM'])){{$shipInfo['BM']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">DM</td>
                                        <td>@if(isset($shipInfo['DM'])){{$shipInfo['DM']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">HEIGHT FM KEEL TO MAST</td>
                                        <td>@if(isset($shipInfo['DeckErection_H'])){{$shipInfo['DeckErection_H']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">SUMMER DRAFT</td>
                                        <td>@if(isset($shipInfo['Draught'])){{$shipInfo['Draught']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">FW_Allowance</td>
                                        <td>{{ isset($freeBoard['new_free_fw']) ? $freeBoard['new_free_fw'] : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">POWER</td>
                                        <td>@if(isset($shipInfo['Power'])){{$shipInfo['Power']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">REGISTRY PORT</td>
                                        <td>@if(isset($shipInfo['PortOfRegistry'])){{$shipInfo['PortOfRegistry']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #f8f8f8;">OWNER</td>
                                        <td>@if(isset($shipInfo['Owner_Cn'])){{$shipInfo['Owner_Cn']}}@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #ffffff; height: 35px; vertical-align: middle;">SHIP'S CERTIFICATES</td>
                                        <td style="vertical-align: middle;">DATE ISSUED</td>
                                        <td style="vertical-align: middle;">DATE VALID</td>
                                    </tr>
                                    @foreach($elseInfo['cert'] as $key => $item)
                                        <tr>
                                            <td style="background-color: #f8f8f8;">{{ $key }}</td>
                                            <td>{{ $item['issue_date'] }}</td>
                                            <td>{{ $item['expire_date'] }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td style="background-color: #ffffff; height: 35px; vertical-align: middle;">CERTIFICATES OF COMPETENCY FOR SEAFARERS</td>
                                        <td style="vertical-align: middle;">NO OF DOC</td>
                                        <td style="vertical-align: middle;">VALID UNTIL</td>
                                    </tr>
                                    @foreach($memberCertXls['COC'] as $key => $item)
                                        <tr>
                                            <td style="background-color: #f8f8f8;">{{ $item[1] }}</td>
                                            @if(isset($elseInfo['member'][$item[0]]))
                                                <td>{{ $elseInfo['member'][$item[0]]['ItemNo'] }}</td>
                                                <td>{{ $elseInfo['member'][$item[0]]['COC_IssueDate'] }}</td>
                                            @else
                                                <td></td>
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach

                                    @foreach($memberCertXls['GOC'] as $key => $item)
                                        <tr>
                                            <td style="background-color: #f8f8f8;">{{ $item[1] }}</td>
                                            @if(isset($elseInfo['member'][$item[0]]))
                                                <td>{{ $elseInfo['member'][$item[0]]['GMDSS_NO'] }}</td>
                                                <td>{{ $elseInfo['member'][$item[0]]['GMD_ExpiryDate'] }}</td>
                                            @else
                                                <td></td>
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="space-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="excel-output" class="d-none"></div>
    @endif

    <script>
        var tab = '';
        var tab_text = '';
		var shipName = '{!! $shipName !!}';
        function shipInfoExcel() {
            tab_text="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            tab = document.getElementById('formA-table').cloneNode(true);
            for(var j = 0 ; j < tab.rows.length ; j++) {
                if (j == 0) {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        if (i == 0) {
                            tab.rows[j].cells[0].style.width = '300px';
                        }
                        else if (i == 1) {
                            tab.rows[j].cells[0].style.width = '300px';
                        }
                        else if (i == 2) {
                            tab.rows[j].cells[0].style.width = '300px';
                        }
                    }
                }
            }

            tab_text += tab.innerHTML;
            tab_text += "</table>";

            // $('#excel-output tr td').css({'width': '300px', 'border-left' : '1px solid #666666', 'border-bottom' : '1px solid #666666'});
            exportExcel(tab_text, shipName + '_' + 'FORM A', 'Sheet');

        }

		function changeTab(type) {
			
			if(type == 'general') {
				$('#excel-general').removeClass('d-none');
				$('#excel-formA').addClass('d-none');
			} else {
				$('#excel-general').addClass('d-none');
				$('#excel-formA').removeClass('d-none');
			}
		}
    </script>
@endsection
