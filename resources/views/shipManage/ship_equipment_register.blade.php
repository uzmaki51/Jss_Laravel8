<?php
$isHolder = Session::get('IS_HOLDER');
$shipList = Session::get('shipList');
?>
@if(!isset($excel))
    <style>
        .editable-input {
            background: transparent!important;
            border: none !important;
        }
        .editing {
            border: 1px solid #c3c3c3!important;
        }
    </style>
    <div class="col-md-12" style="margin-top: 20px;">
        <ul class="nav nav-tabs">
            <li class="nav-item active">
                <a href="#supply_Tab" data-toggle="tab">{{ transShipManager("EquipmentManage.supply") }}表</a>
            </li>
            <li class="nav-item">
                <a href="#diligence_Tab" data-toggle="tab">{{ transShipManager("EquipmentManage.diligence") }}表</a>
            </li>
            @if(!$isHolder)
                <button class="btn btn-sm btn-primary" id="new_btn" onclick="newEquipment()" style="float:right;width :80px">
                    <i class="icon-plus-sign-alt"></i>
                    添加
                </button>
            @endif
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="supply_Tab">
            <div class="col-md-12" style="overflow-y: scroll; width: 100%">
                @else
                    @include('layout.excel-header-include')
                    @include('layout.excel-style')
                @endif

                <table class="arc-std-table table table-bordered" style="font-size: 12px" id="equipement_table_body">
                    <thead>
                    <tr class="black br-hblue">
                        <th class="center" rowspan="2" style="width:3%">No</th>
                        <th class="center" rowspan="2" style="width:4%">{{ transShipManager("EquipmentTypeManage.type")}}</th>
                        <th class="center" style="width:15%">{{transShipManager("EquipmentManage.Equipment_Cn")}}</th>
                        <th class="center" style="width:12%">{{transShipManager("EquipmentManage.Label")}}</th>
                        <th class="center" rowspan="2" style="width:12%">{{transShipManager("EquipmentManage.IssaCode")}}</th>
                        <th class="center" rowspan="2" style="width: 15%">{{transShipManager("EquipmentManage.supplied_at")}}</th>
                        <th class="center" rowspan="2" style="width:4%">{{transShipManager("EquipmentManage.Qty")}}</th>
                        <th class="center" rowspan="2" style="width:4%">{{transShipManager("EquipmentManage.unit")}}</th>
                        @if(!isset($excel) && !$isHolder)
                            <th class="center" rowspan="2" style="width:4%">{{ transShipManager("EquipmentManage.operation") }}</th>
                        @endif
                    </tr>
                    <tr class="black br-hblue">
                        <th class="center" style="width:15%">{{ transShipManager("EquipmentManage.Equipment_en")}} </th>
                        <th class="center" style="width:12%">{{transShipManager("EquipmentManage.S/N")}}</th>
                    </tr>
                    </thead>
                    @if(!isset($excel))
                </table>
            </div>
            <div class="col-md-12" style="overflow-x:hidden; overflow-y:scroll; width:100%; height:25vh;">
                <table class="table table-bordered table-striped" style="border-top:0">
                    @endif
                    <tbody id="register_table">
					<?php
					if(!isset($excel)) $index = 1;
					else $index = 0;
					?>
                    @if(isset($registeredList) && count($registeredList) > 0)
                        @foreach($registeredList as $equipment)
                            <tr data-index-1="{{ $equipment['id'] }}">
                                <td class="center" rowspan="2" style="width:3%">{{$index++}}</td>
                                {{--@if(!isset($excel))--}}
                                {{--<td class="hidden" data-kind="{{$equipment['KindId']}}" data-sub="{{$equipment['subKind']}}">{{$equipment['id']}}</td>--}}
                                {{--@endif--}}
                                <td class="center" rowspan="2" style="width:4%">{{ isset($kindLabelList[$equipment['KindOfEuipmentId']]) ? $kindLabelList[$equipment['KindOfEuipmentId']] : '未定' }}</td>
                                <td class="center" style="width:15%">{{$equipment['Euipment_Cn']}}</td>
                                <td class="center" style="width:12%">{{$equipment['Label']}}</td>
                                <td class="center" rowspan="2" style="width: 12%;">{{$equipment['IssaCodeNo']}}</td>
                                <td class="center" rowspan="2" style="width: 15%">
                                    <input class="editable-input text-center" type="text" value="{{ isset($equipment['supplied_at']) ? $equipment['supplied_at'] : transShipManager("EquipmentManage.not_registered") }}" readonly data-id="{{ $equipment['id'] }}" data-inputmask="'mask': '9999-99-99'"/>
                                </td>
                                <td class="center" rowspan="2" style="width:4%"><span class="badge badge-primary">{{ $equipment['Qty'] }}</span></td>
                                <td class="center" rowspan="2" style="width:4%">{{ $equipment['Unit'] }}</td>


                                @if(!isset($excel) && !$isHolder)
                                    <td rowspan="2" style="width:4%">
                                        <div class="action-buttons text-center">
                                            <a class="blue" href="getEquipmentDetail?equipId={{$equipment['id']}}&shipId={{$shipId}}">
                                                <i class="icon-edit bigger-130"></i>
                                            </a>
                                            <a class="red" href="javascript:deleteItem('{{ $equipment['Euipment_Cn'] }}', '{{ $equipment['id'] }}', 'supply')">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                            <tr data-index-2="{{ $equipment['id'] }}">
                                <td>{{$equipment['Euipment_En']}}</td>
                                {{--<td class="center">{{$equipment['Type']}}</td>--}}
                                <td class="center" style="width:12%">{{$equipment['SN']}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">
                                {{ trans('common.message.no_data') }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                @if(!isset($excel))
            </div>
        </div>
        <div class="tab-pane" id="diligence_Tab">
            <div class="col-md-12" style="overflow-y: scroll; width: 100%">
                @else
                    @include('layout.excel-header-include')
                    @include('layout.excel-style')
                @endif

                <table class="arc-std-table table table-bordered" style="font-size: 12px" id="equipement_table_body">
                    <thead>
                    <tr class="black br-hblue">
                        <th class="center" rowspan="2" style="width:3%">No</th>
                        <th class="center" rowspan="2" style="width:4%">{{ transShipManager("EquipmentTypeManage.type")}}</th>
                        <th class="center" style="width:15%">{{transShipManager("EquipmentManage.Equipment_Cn")}}</th>
                        <th class="center" style="width:12%">{{transShipManager("EquipmentManage.Label")}}</th>
                        <th class="center" rowspan="2" style="width:12%">{{transShipManager("EquipmentManage.IssaCode")}}</th>
                        <th class="center" rowspan="2" style="width:8%">{{transShipManager("EquipmentManage.diligence_at")}}</th>
                        <th class="center" rowspan="2" style="width:4%">{{transShipManager("EquipmentManage.remain_count")}}</th>
                        <th class="center" rowspan="2" style="width:4%">{{transShipManager("EquipmentManage.unit")}}</th>
                        <th class="center" rowspan="2" style="width:4%">{{transShipManager("EquipmentManage.status")}}</th>
                        @if(!isset($excel) && !$isHolder)
                            <th class="center" rowspan="2" style="width:4%">{{ transShipManager("EquipmentManage.operation") }}</th>
                        @endif
                    </tr>
                    <tr class="black br-hblue">
                        <th class="center" style="width:15%">{{ transShipManager("EquipmentManage.Equipment_en")}} </th>
                        <th class="center" style="width:12%">{{transShipManager("EquipmentManage.S/N")}}</th>
                    </tr>
                    </thead>
                    @if(!isset($excel))
                </table>
            </div>
            <div class="col-md-12" style="overflow-x:hidden; overflow-y:scroll; width:100%; height:25vh;">
                <table class="table table-bordered table-striped table-hover" style="border-top:0">
                    @endif
                    <tbody id="device_table">
					<?php
					if(!isset($excel)) $index = 1;
					else $index = 0;
					?>
                    @if(isset($diligenceList) && count($diligenceList) > 0)
                        @foreach($diligenceList as $equipment)
                            <tr diligence-index-1="{{ $equipment['id'] }}">
                                <td class="center" rowspan="2" style="width:3%">{{$index++}}</td>
                                {{--@if(!isset($excel))--}}
                                {{--<td class="hidden" data-kind="{{$equipment['KindId']}}" data-sub="{{$equipment['subKind']}}">{{$equipment['id']}}</td>--}}
                                {{--@endif--}}
                                <td class="center" rowspan="2" style="width:4%">{{ isset($kindLabelList[$equipment['KindOfEuipmentId']]) ? $kindLabelList[$equipment['KindOfEuipmentId']] : '未定' }}</td>
                                <td class="center" style="width:15%">{{$equipment['Euipment_Cn']}}</td>
                                <td class="center" style="width:12%">{{$equipment['Label']}}</td>
                                <td class="center" rowspan="2" style="width: 12%;">{{$equipment['IssaCodeNo']}}</td>
                                <td class="center" rowspan="2" style="width:8%">{{ _convertDateFormat($equipment['diligence_at'], 'Y-m-d') }}</td>
                                <td class="center" rowspan="2" style="width:4%"><span class="badge badge-primary">{{ $equipment['remain_count'] }}</span></td>
                                <td class="center" rowspan="2" style="width:4%">{{ $equipment['Unit'] }}</td>
                                <td class="center" rowspan="2" style="width:4%"><span class="badge badge-{{ g_enum('InventoryStatusData')[$equipment['Status']][1] }}">{{ g_enum('InventoryStatusData')[$equipment['Status']][0] }}</span></td>


                                @if(!isset($excel) && !$isHolder)
                                    <td rowspan="2" style="width:4%">
                                        <div class="action-buttons text-center">
                                            <a class="blue" href="getDiligenceDetail?equipId={{$equipment['id']}}&shipId={{$shipId}}">
                                                <i class="icon-edit bigger-130"></i>
                                            </a>
                                            <a class="red" href="javascript: deleteItem('{{ $equipment['Euipment_Cn'] }}', '{{ $equipment['id'] }}', 'diligence')">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                            <tr diligence-index-2="{{ $equipment['id'] }}">
                                <td>{{$equipment['Euipment_En']}}</td>
                                <td class="center" style="width:12%">{{$equipment['SN']}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">
                                {{ trans('common.message.no_data') }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                @if(!isset($excel))
            </div>
        </div>
    </div>

    <div id="supply-date" class="modal fade" tabindex="-1">
        <div class="modal-dialog" style="width:80%;padding-top:12%" >
            <div class="modal-content">
                <div class="modal-header no-padding">
                    <div class="table-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <span class="white">&times;</span>
                        </button>
                        <span name="diligence_title"></span>
                    </div>
                </div>
                <div class="modal-body no-padding">
                    <form action="appendNewShipDiligenceEquipment" method="POST" id="equipment_form">
                        <input type="text" class="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="text" class="hidden" name="shipId" value="@if(isset($shipId)){{$shipId}}@endif">
                        <table class="arc-std-table table table-striped table-bordered" style="text-align:center;">
                            <thead>
                            <tr>
                                <td style="width:80px; ">{{ transShipManager('EquipmentManage.Equipment Type') }}<span class="require">*</span></td>
                                {{--<td>{{ transShipManager('EquipmentManage.Tool Type') }}<span class="require">*</span></td>--}}
                                {{--<td>{{ transShipManager('EquipmentManage.PIC') }}</td>--}}
                                <td>{{ transShipManager('EquipmentManage.Equipment_Cn') }}<span class="require">*</span></td>
                                <td>{{ transShipManager('EquipmentManage.Equipment_en') }}<span class="require">*</span></td>
                                <td>{{ transShipManager('EquipmentManage.Label') }}<span class="require">*</span></td>
                                <td>{{ transShipManager('EquipmentManage.S/N') }}</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select class="form-control" name="mainKind">
                                        @foreach($allKind as $kind)
                                            <option value="{{$kind['id']}}">{{$kind['Kind_Cn']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                {{--<td>--}}
                                {{--<select class="form-control" name="subKind">--}}
                                {{--@foreach($allKind[0]['subKind'] as $kind)--}}
                                {{--<option value="{{$kind['id']}}">{{$kind['GroupOfEuipment_Cn']}}</option>--}}
                                {{--@endforeach--}}
                                {{--</select>--}}
                                {{--</td>--}}
                                {{--<td><input type="text" class="form-control" name="PIC" style="width:100%;"></td>--}}
                                <td><input type="text" class="form-control" name="Euipment_Cn" style="width:100%;"></td>
                                <td><input type="text" class="form-control" name="Euipment_En" style="width:100%;"></td>
                                <td><input type="text" class="form-control" name="Label" style="width:100%;"></td>
                                <td><input type="text" class="form-control" name="SN" style="width:100%;"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="arc-std-table table table-striped table-bordered">
                            <thead>
                            <tr>
                                <td>{{ transShipManager('EquipmentManage.unit') }}<span class="require">*</span></td>
                                <td>{{ transShipManager('EquipmentManage.remain_count') }}</td>
                                <td>{{ transShipManager('EquipmentManage.IssaCode') }}<span class="require">*</span></td>
                                <td>{{ transShipManager('EquipmentManage.status') }}</td>
                                <td>{{ transShipManager('EquipmentManage.diligence_at') }}<span class="require">*</span></td>
                                <td>{{ transShipManager('EquipmentManage.Remark') }}</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" class="form-control" name="Unit" style="width:100%;"></td>
                                <td><input type="text" class="form-control" name="remain_count" style="width:100%;"></td>
                                <td><input type="text" class="form-control" name="IssaCodeNo" style="width:100%;"></td>
                                <td>
                                    <select  class="form-control" name="Status" style="width:100%;">
                                        @foreach(g_enum('InventoryStatusData') as $key => $item)
                                            <option value="{{ $key }}">{{ $item[0] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="diligenceDate" style="width:100%;"></td>
                                <td><input type="text" class="form-control" name="Remark" style="width:100%;"></td>
                            </tr>
                            </tbody>
                        </table>
                        <button class="hidden" id='diligence_submit_btn'></button>
                    </form>
                </div>
                <div class="modal-footer padding-8" style="text-align: right">
                    <button class="btn btn-primary btn-sm btn-warning" data-dismiss="modal">取消</button>
                    <button class="btn btn-primary btn-sm btn-danger save_btn">登记</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
    <script src="{{ asset('/assets/js/jquery.inputlimiter.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.maskedinput.min.js') }}"></script>
    <script>
        var not_register = '{{ transShipManager("EquipmentManage.not_registered") }}';
        $(function () {
            // $('.delete_btn').on('click', function() {
            //     var obj = $(this).closest('tr').children();
            //     deleteShipEquipment(obj);
            // });
            $(":input").inputmask();
            $('.nav-item').click(function() {
                registerType = !registerType;
            })
        });

        function deleteItem(equipName, id, type) {console.log(equipName, id, type)
            bootbox.confirm(equipName + " 真要删除吗?", function (result) {
                if (result) {
                    $.post('deleteShipEquipment', {'_token':token, 'deviceId':id, 'type': type}, function (result) {
                        var code = parseInt(result);
                        if (code > 0) {
                            $.gritter.add({
                                title: '成功',
                                text: '[' + equipName + '] 删除成功!',
                                class_name: 'gritter-success'
                            });
                            if(type == 'supply') {
                                $('[data-index-1=' + code + ']').remove();
                                $('[data-index-2=' + code + ']').remove();
                            } else {
                                $('[diligence-index-1=' + code + ']').remove();
                                $('[diligence-index-2=' + code + ']').remove();
                            }

                        } else {
                            $.gritter.add({
                                title: '错误',
                                text: '[' + equipName + '] 是已经被删除的。',
                                class_name: 'gritter-error'
                            });
                        }
                    });
                }
            });
        }


        $('.editable-input').click(function(e) {
            e.preventDefault();
            var $count = 0;

            var $eb = $('.edit-button');
            var $ei = $('.editable-input');
            var $ec = $('.editable-cell');

            $(this).prop('readonly', false).focus();
            $(this).select();
            $(this).addClass('editing');
        });

        $('.editable-input').on('blur', function() {
            $(this).removeClass('hide');
            $(this).prop('readonly', true);
            $(this).removeClass('editing');
            if($(this).val() == "0000-00-00") {
                return;
            } else {
                $.ajax({
                    url: BASE_URL + 'shipManage/ajax/shipEquipment/SupplyDate/update',
                    type: 'post',
                    data: {
                        id: $(this).attr('data-id'),
                        date: $(this).val()
                    },
                    success: function(result, status, xhr) {
                        console.log(result);
                    },
                    error: function(error, status) {
                        console.log(error);
                    }
                })
            }
        });

    </script>
    @else
    </body>
    </html>
@endif