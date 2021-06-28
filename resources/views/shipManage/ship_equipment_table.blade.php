@if(!isset($excel))

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
                <th class="center" style="width:12%">{{transShipManager("EquipmentManage.S/N")}}</th>
                <th class="center" rowspan="2" style="width:8%">{{transShipManager("EquipmentManage.supply")}}({{transShipManager("EquipmentManage.date")}})</th>
                <th class="center" rowspan="2" style="width:4%">{{transShipManager("EquipmentManage.supply")}}({{transShipManager("EquipmentManage.Qty")}})</th>
                <th class="center" rowspan="2" style="width:8%">{{transShipManager("EquipmentManage.diligence")}}({{transShipManager("EquipmentManage.date")}})</th>
                <th class="center" rowspan="2" style="width:4%">{{transShipManager("EquipmentManage.diligence")}}({{transShipManager("EquipmentManage.Qty")}})</th>
                <th class="center" rowspan="2" style="width:8%">{{transShipManager("EquipmentManage.diligence")}}({{transShipManager("EquipmentManage.status")}})</th>
                {{--@if(!isset($excel))--}}
                {{--<th class="center" rowspan="2" style="width:4%">{{ transShipManager("EquipmentManage.operation") }}</th>--}}
                {{--@endif--}}
            </tr>
            <tr class="black br-hblue">
                <th class="center" style="width:15%">{{ transShipManager("EquipmentManage.Equipment_en")}} </th>
                <th class="center" style="width:12%">{{transShipManager("EquipmentManage.Type/Model")}}</th>
                <th class="center" style="width:12%">{{transShipManager("EquipmentManage.IssaCode")}}</th>
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
            @if(isset($list) && count($list) > 0)
                @foreach($list as $equipment)
                    <tr>
                        <td class="center" rowspan="2" style="width:3%">{{$index++}}</td>
                        {{--@if(!isset($excel))--}}
                        {{--<td class="hidden" data-kind="{{$equipment['KindId']}}" data-sub="{{$equipment['subKind']}}">{{$equipment['id']}}</td>--}}
                        {{--@endif--}}
                        <td class="center" rowspan="2" style="width:4%">{{ isset($kindLabelList[$equipment['KindOfEuipmentId']]) ? $kindLabelList[$equipment['KindOfEuipmentId']] : '未定' }}</td>
                        <td class="center" style="width:15%"><a href="javascript:showEquepmentDetail('{{$equipment['id']}}', '{{$equipment['Euipment_Cn']}}', '{{$equipment['IssaCodeNo']}}', '{{$equipment['ShipRegNo']}}')">{{$equipment['Euipment_Cn']}}</a></td>
                        <td class="center" style="width:12%">{{$equipment['Label']}}</td>
                        <td class="center" style="width:12%">{{$equipment['SN']}}</td>
                        <td class="center" rowspan="2" style="width:8%">{{ _convertDateFormat($equipment['supplied_at'], 'Y-m-d') }}</td>
                        <td class="center" rowspan="2" style="width:4%"><span class="badge badge-primary">{{ $equipment['Qty'] }}</span></td>
                        @if(isset($equipment['Status']))
                            <td class="center" rowspan="2" style="width:8%">{{ isset($equipment['Status']) ? _convertDateFormat($equipment['diligence_at'], 'Y-m-d') : trans('common.label.nothing') }}</td>
                            <td class="center" rowspan="2" style="width:4%"><span class="badge badge-danger">{{ isset($equipment['Status']) ? $equipment['remain_count'] : trans('common.label.nothing') }}</span></td>
                            <td class="center" rowspan="2" style="width:8%"><span class="badge badge-{{ g_enum('InventoryStatusData')[$equipment['Status']][1] }}">{{ g_enum('InventoryStatusData')[$equipment['Status']][0] }}</span></td>
                        @else
                            <td class="center" rowspan="2" colspan="4" style="width:20%">{{ trans('common.label.nothing') . transShipManager("EquipmentManage.diligence") . trans('common.label.data') }}。</td>
                        @endif
                        {{--@if(!isset($excel))--}}
                        {{--<td rowspan="2" style="width:4%">--}}
                        {{--<div class="action-buttons">--}}
                        {{--<a class="blue" href="getEquipmentDetail?equipId={{$equipment['id']}}&shipId={{$shipId}}">--}}
                        {{--<i class="icon-edit bigger-130"></i>--}}
                        {{--</a>--}}
                        {{--<a class="red delete_btn">--}}
                        {{--<i class="icon-trash bigger-130"></i>--}}
                        {{--</a>--}}
                        {{--</div>--}}
                        {{--</td>--}}
                        {{--@endif--}}
                    </tr>
                    <tr>
                        <td>{{$equipment['Euipment_En']}}</td>
                        <td class="center">{{$equipment['Type']}}</td>
                        <td class="center">{{$equipment['IssaCodeNo']}}</td>
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

    <script>
        $(function () {
            $('.delete_btn').on('click', function() {
                var obj = $(this).closest('tr').children();
                deleteShipEquipment(obj);
            });
        })

    </script>
    @else
    </body>
    </html>
@endif