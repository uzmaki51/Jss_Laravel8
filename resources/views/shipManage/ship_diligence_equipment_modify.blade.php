@extends('layout.header')

@section('content')
    <style>
        .chosen-drop {
            width: 500px !important;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>{{ transShipManager('title.equipment') }}</b>
                        <label>
                            <i class="icon-double-angle-right"></i>
                            各船舶设备目录
                        </label>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            设备详细
                        </small>
                    </h4>
                </div>
                <div class="col-md-6 alert alert-block alert-info center" style="font-size: 16px">
                    <strong>《&nbsp;{{$shipName['name']}}({{$shipName['shipName_Cn']}}
                        )&nbsp;》&nbsp;({{ $shipName['shipName_En'] }}) 设备 </strong>
                </div>
                <div class="col-sm-3">
                    <h5 style="float: right"><a href="javascript: history.back()"><strong>上一页</strong></a></h5>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="widget-box">
                        <div class="widget-header header-color-green3">
                            <h4 class="lighter smaller col-md-4">{{ transShipManager('EquipmentDetail.Equipment') }}</h4>
                        </div>
                        <form action="appendNewShipDiligenceEquipment" method="POST" id="equipment_form">
                            <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="text" class="hidden" name="id" value="{{ $device['id'] }}">
                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <form action="appendNewShipEquipment" method="POST" id="equipment_form">
                                        <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="text" class="hidden" name="equipId" value="{{$device['id']}}">
                                        <input type="text" class="hidden" name="shipId" value="{{$shipId}}">
                                        <input type="submit" class="hidden" id="submit_btn">
                                        <table class="arc-std-table table table-striped table-bordered">
                                            <thead>
                                            <tr class="black br-hblue">
                                                <th style="width:80px">{{ transShipManager('EquipmentDetail.Dept') }}<span
                                                            class="require">*</span></th>
                                                <th>{{ transShipManager('EquipmentDetail.Equipment_Cn') }}<span
                                                            class="require">*</span></th>
                                                <th>{{ transShipManager('EquipmentDetail.Equipment_en') }}<span
                                                            class="require">*</span></th>
                                                <th>{{ transShipManager('EquipmentDetail.Label') }}<span
                                                            class="require">*</span></th>
                                                <th>{{ transShipManager('EquipmentDetail.S/N') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control chosen-select" name="mainKind">
                                                        @foreach($mainKinds as $kind)
                                                            <option value="{{$kind['id']}}"
                                                                    @if($kind['id'] == $device['KindOfEuipmentId']) selected @endif>{{$kind['Kind_Cn']}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td><input type="text" class="form-control" name="Euipment_Cn"
                                                           style="width:100%;" value="{{$device['Euipment_Cn']}}"></td>
                                                <td><input type="text" class="form-control" name="Euipment_En"
                                                           style="width:100%;" value="{{$device['Euipment_En']}}"></td>
                                                <td><input type="text" class="form-control" name="Label"
                                                           style="width:100%;text-align: center"
                                                           value="{{$device['Label']}}"></td>
                                                <td><input type="text" class="form-control" name="SN"
                                                           style="width:100%;text-align: center" value="{{$device['SN']}}">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="arc-std-table table table-striped table-bordered"
                                               style="border-top: 1px solid #e5e5e5;">
                                            <thead>
                                            <tr class="black br-hblue">
                                                <th>{{ transShipManager('EquipmentManage.unit') }}</th>
                                                <th>{{ transShipManager('EquipmentDetail.Qty') }}</th>
                                                <th>{{ transShipManager('EquipmentDetail.IssaCode') }}</th>
                                                <th>{{ transShipManager('EquipmentManage.status') }}</th>
                                                <th>{{ transShipManager('EquipmentManage.supplied_at') }}</th>
                                                <th>{{ transShipManager('EquipmentDetail.Remark') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                {{--<td><input type="text" class="form-control" name="Type"--}}
                                                {{--style="width:100%;text-align: center"--}}
                                                {{--value="{{$device['Type']}}"></td>--}}
                                                <td><input type="text" class="form-control" name="Unit"
                                                           style="width:100%;text-align: center"
                                                           value="{{$device['Unit']}}"></td>
                                                <td><input type="text" class="form-control" name="remain_count"
                                                           style="width:100%;text-align: center" value="{{$device['remain_count']}}">
                                                </td>
                                                <td>
                                                    <div style="width: 100%;">
                                                        <input class="form-control" name="IssaCodeNo" id="IssaCodeNo" value="{{ $device['IssaCodeNo'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select  class="form-control" name="Status" style="width:100%;">
                                                        @foreach(g_enum('InventoryStatusData') as $key => $item)
                                                            <option value="{{ $key }}">{{ $item[0] }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="ManufactureDate"
                                                           style="width:100%;text-align: center"
                                                           value="{{$device['supplied_at']}}"></td>
                                                <td><input type="text" class="form-control" name="Remark"
                                                           style="width:100%;text-align: center"
                                                           value="{{$device['Remark']}}"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="space-6"></div>
                                        <div style="display: flex;">
                                            <button type="submit" class="btn btn-xs btn-inverse" style="width:80px; margin-left: auto;">
                                                <i class="icon-save"></i>
                                                登记
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
