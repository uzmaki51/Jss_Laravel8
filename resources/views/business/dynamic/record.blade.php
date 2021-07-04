<?php
    if(isset($excel)) $header = 'excel-header';
    else $header = 'header';
?>

<?php
    $isHolder = Session::get('IS_HOLDER');
    $ships = Session::get('shipList');
?>

@extends('layout.'.$header)

@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/vue.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
@endsection


@section('content')
    <div class="main-content">
        <style>
            .filter_row {
                background-color: #45f7ef;
            }
            .chosen-drop {
                width : 350px !important;
            }
        </style>
        <div class="page-header">
            <div class="col-md-3">
                <h4>
                    <b>动态记录</b>
                </h4>
            </div>
        </div>

        <div class="page-content" id="search-div">
            <div class="row">
                <div class="col-md-12 align-bottom" v-cloak>
                    <div class="col-md-3">
                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名:</label>
                        <select class="custom-select d-inline-block" style="padding: 4px;max-width: 100px;" @change="changeShip" v-model="shipId">
                            @foreach($shipList as $ship)
                                <option value="{{ $ship['IMO_No'] }}"
                                        {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                                </option>
                            @endforeach
                        </select>
                        <label class="font-bold">航次:</label>
                            <select class="text-center" style="width: 60px;" name="voy_list" @change="onChangeVoy" v-model="activeVoy">
                                <template v-for="voyItem in voy_list">
                                    <option :value="voyItem.Voy_No">@{{ voyItem.Voy_No }}</option>
                                </template>
                            </select>                        
                        @if(isset($shipName['shipName_En']))
                            <strong class="f-right" style="font-size: 16px; padding-top: 6px;">"<span id="ship_name">{{ $shipName['shipName_En'] }}</span>" CERTIFICATES</strong>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex f-right">
                            <strong class="f-right" style="font-size: 16px; padding-top: 6px;">
                                <span id="search_info">"{{ $shipName }}"</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font-style-italic font-bold">DAILY REPORT</span>
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="" style="margin-right: 12px; padding-top: 2px;">
                            <table class="contract-table mt-2 table-layout-fixed" style="min-height: auto;">
                            <tr>
                                <td class="width-10">装港</td>
                                <td class="font-style-italic width-30">LOADING PORT</td>
                                <td class="font-style-italic width-50 text-ellipsis white-bg" style="border-right: 1px solid #4c4c4c;">@{{ port['loading'] }}</td>
                            </tr>
                            <tr>
                                <td style="width-10">卸港</td>
                                <td class="font-style-italic width-30">DISCHARGING PORT</td>
                                <td class="font-style-italic width-50 text-ellipsis white-bg" style="border-right: 1px solid #4c4c4c;">@{{ port['discharge'] }}</td>
                            </tr>                            
                            </table>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="btn-group f-right">
                            <button class="btn btn-report-search btn-sm search-btn d-none" click="doSearch()"><i class="icon-search"></i>搜索</button>
                            <a class="btn btn-sm btn-danger refresh-btn-over d-none" type="button" click="refresh">
                                <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">恢复
                            </a>
                            <button class="btn btn-warning btn-sm save-btn" @click="submitForm"><i class="icon-save"></i> {{ trans('common.label.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Contents Begin -->
            <div class="row col-lg-12" style="margin-top: 4px;">
                <div class="head-fix-div common-list">
                <form action="saveDynamic" method="post" id="dynamic-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="shipId" value="{{ $shipId }}">
                    <input type="hidden" name="CP_ID" v-model="activeVoy">
                    <table class="table-bordered dynamic-table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center font-style-italic">VOY No</th>
                                <th class="text-center font-style-italic">DATE</th>
                                <th class="text-center font-style-italic" colspan="2">TIME[LT]</th>
                                <th class="text-center font-style-italic" rowspan="2">GMT</th>
                                <th class="text-center font-style-italic">STATUS</th>
                                <th class="text-center">状态</th>
                                <th class="text-center font-style-italic">POSITION</th>
                                <th class="text-center font-style-italic">DTG[NM]</th>
                                <th class="text-center font-style-italic">SPEED</th>
                                <th class="text-center font-style-italic" style="width: 45px;">RPM</th>
                                <th class="text-center font-style-italic" style="border-right: 2px solid #4c4c4c; width: 48px;">CGO QTY</th>
                                <th class="text-center font-style-italic" colspan="2">ROB</th>
                                <th class="text-center font-style-italic" colspan="2">BUNKERING</th>
                                <th class="text-center font-style-italic" colspan="4" style="width: 20%;">REMARK</th>
                                <th style="width: 16px;"></th>
                            </tr>
                            <tr>
                                <th class="text-center">航次</th>
                                <th class="text-center font-style-italic" style="width: 77px;">YY/MM/DD</th>
                                <th class="text-center font-style-italic">hh</th>
                                <th class="text-center font-style-italic">mm</th>
                                <th class="text-center">动态</th>
                                <th class="text-center">种类</th>
                                <th class="text-center">港口(坐标)</th>
                                <th class="text-center">距离</th>
                                <th class="text-center">速度</th>
                                <th class="text-center">转数</th>
                                <th class="text-center" style="border-right: 2px solid #4c4c4c;">存货量</th>
                                <th class="text-center font-style-italic">FO</th>
                                <th class="text-center font-style-italic">DO</th>
                                <th class="text-center font-style-italic">FO</th>
                                <th class="text-center font-style-italic">DO</th>
                                <th class="text-center" colspan="4"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="prev-voy">
                                <td class="text-center">@{{ prevData['CP_ID'] }}</td>
                                <td class="text-center">@{{ prevData['Voy_Date'] }}</td>
                                <td class="text-center">@{{ prevData['Voy_Hour'] }}</td>
                                <td class="text-center">@{{ prevData['Voy_Minute'] }}</td>
                                <td class="text-center">@{{ prevData['GMT'] }}</td>
                                <td style="padding-left: 8px!important">@{{ prevData['Voy_Status'] }}</td>
                                <td style="padding-left: 8px!important">@{{ prevData['Voy_Type'] }}</td>
                                <td style="padding-left: 4px!important">@{{ prevData['Ship_Position'] }}</td>
                                <td class="text-center">@{{ prevData['Sail_Distance'] }}</td>
                                <td class="text-center">@{{ prevData['Speed'] }}</td>
                                <td class="text-center">@{{ prevData['RPM'] }}</td>
                                <td class="text-right font-weight-bold text-danger" style="border-right: 2px solid #484f5b;">@{{ prevData['Cargo_Qtty'] }}</td>
                                <td class="text-center font-weight-bold text-danger">@{{ prevData['ROB_FO'] }}</td>
                                <td class="text-center font-weight-bold text-danger">@{{ prevData['ROB_DO'] }}</td>
                                <td class="text-center">@{{ prevData['BUNK_FO'] }}</td>
                                <td class="text-center">@{{ prevData['BUNK_DO'] }}</td>
                                <td colspan="4">@{{ prevData['Remark'] }}</td>
                                <td></td>
                            </tr>
                            <template v-for="(currentItem, index) in currentData">
                                <tr class="dynamic-item" :class="index % 2 == 0 ? 'cost-item-even' : 'cost-item-odd'">
                                    <td class="d-none"><input type="hidden" :value="currentItem.id" name="id[]"></td>
                                    <td class="text-center"><input type="text" readonly  v-model="activeVoy" name="CP_ID[]" class="form-control text-center"></td>
                                    <td class="text-center date-width"><input type="text" class="date-picker form-control text-center" name="Voy_Date[]" v-model="currentItem.Voy_Date" @click="dateModify($event, index)" data-date-format="yyyy-mm-dd"></td>
                                    <td class="time-width"><input type="number" class="form-control text-center hour-input" name="Voy_Hour[]" v-model="currentItem.Voy_Hour" @blur="limitHour($event, index)" @keyup="limitHour($event, index)"></td>
                                    <td class="time-width"><input type="number" class="form-control text-center minute-input" name="Voy_Minute[]" v-model="currentItem.Voy_Minute" @blur="limitMinute($event, index)" @keyup="limitMinute($event, index)"></td>
                                    <td class="time-width"><input type="number" class="form-control text-center gmt-input" name="GMT[]" v-model="currentItem.GMT" @blur="limitGMT($event, index)" @keyup="limitGMT($event, index)"></td>
                                    <td>
                                        <select type="number" class="form-control" name="Voy_Status[]" v-model="currentItem.Voy_Status" @change="onChangeStatus($event, index)" style="width: 120px;">
                                            <option v-for="(item, index) in dynamicStatus" v-bind:value="index">@{{ item[0] }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select type="number" class="form-control" name="Voy_Type[]" v-model="currentItem.Voy_Type" style="width: 55px;">
                                            <option v-for="(item, index) in currentItem.dynamicSub" v-bind:value="item[0]">@{{ item[1] }}</option>
                                        </select>
                                    </td>
                                    <td class="position-width"><input type="text" maxlength="25" class="form-control" name="Ship_Position[]" v-model="currentItem.Ship_Position" autocomplete="off"></td>
                                    <td><input type="number" max="100000" class="form-control text-center" name="Sail_Distance[]" v-model="currentItem.Sail_Distance"></td>
                                    <td><input type="number" class="form-control text-center" name="Speed[]" v-model="currentItem.Speed"></td>
                                    <td><input type="number" class="form-control text-center" name="RPM[]" v-model="currentItem.RPM"></td>
                                    <td style="border-right: 2px solid #484f5b;"><input type="number" class="form-control text-right font-weight-bold" :style="currentItem.Voy_Status == '13' ? 'color: red!important' : ''" name="Cargo_Qtty[]" v-model="currentItem.Cargo_Qtty"></td>
                                    <td><input type="number" class="form-control text-center font-weight-bold" :style="currentItem.Voy_Status == '13' ? 'color: red!important' : ''" name="ROB_FO[]" v-model="currentItem.ROB_FO"></td>
                                    <td><input type="number" class="form-control text-center font-weight-bold" :style="currentItem.Voy_Status == '13' ? 'color: red!important' : ''" name="ROB_DO[]" v-model="currentItem.ROB_DO"></td>
                                    <td><input type="number" class="form-control text-center" name="BUNK_FO[]"  style="color: blue!important" v-model="currentItem.BUNK_FO"></td>
                                    <td><input type="number" class="form-control text-center" name="BUNK_DO[]"  style="color: blue!important" v-model="currentItem.BUNK_DO"></td>
                                    <td class="position-width" colspan="4"><textarea class="form-control" name="Remark[]" rows="1" style="resize: none" @click="addRow(index)" maxlength="50" autocomplete="off" v-model="currentItem.Remark"></textarea></td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a class="red" @click="deleteItem(currentItem.id, index)" :class="deleteClass">
                                                <i class="icon-trash" style="color: red!important;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr class="dynamic-footer">
                                <td class="text-center not-striped-td" rowspan="2">航次</td>
                                <td class="text-center not-striped-td" rowspan="2">报告次</td>
                                <td class="text-center not-striped-td" rowspan="2" colspan="5">时间</td>
                                <td class="text-center not-striped-td" rowspan="2">航次用时</td>
                                <td class="text-center not-striped-td" rowspan="2">距离<br>[NM]</td>
                                <td class="text-center not-striped-td" rowspan="2">平均<br>速度</td>
                                <td class="text-center fix-top not-striped-td">经济天</td>
                                <td class="text-center fix-top not-striped-td" style="border-right: 2px solid rgb(72, 79, 91);"><span class="text-warning" :class="dangerClass(economic_rate)">@{{ number_format(economic_rate) }}%</span></td>
                                <td class="text-center fix-top not-striped-td" colspan="2">总消耗</td>
                                <td class="text-center fix-top not-striped-td" colspan="2">加油量</td>
                                <td class="text-center fix-top not-striped-td" colspan="2">标准消耗</td>
                                <td class="text-center fix-top not-striped-td" colspan="2">-节约/+超过</td>
                                <td class="text-center not-striped-td" rowspan="2" colspan="2"></td>
                            </tr>
                            <tr class="dynamic-footer">
                                <td class="text-center not-striped-td">航行</td>
                                <td class="text-center not-striped-td" style="border-right: 2px solid rgb(72, 79, 91);">装卸货</td>
                                <td class="text-center not-striped-td">FO</td>
                                <td class="text-center not-striped-td">DO</td>
                                <td class="text-center not-striped-td">FO</td>
                                <td class="text-center not-striped-td">DO</td>
                                <td class="text-center not-striped-td">FO</td>
                                <td class="text-center not-striped-td">DO</td>
                                <td class="text-center not-striped-td">FO</td>
                                <td class="text-center not-striped-td">DO</td>
                            </tr>
                            <tr class="dynamic-footer-result">
                                <td>@{{ activeVoy }}</td>
                                <td>@{{ number_format(total_count, 0) }}</td>
                                <td colspan="5">@{{ sail_term['min_date'] }} ~ @{{ sail_term['max_date'] }}</td>
                                <td :class="dangerClass(sail_time)">@{{ number_format(sail_time, 2) }}</td>
                                <td :class="dangerClass(total_distance)">@{{ number_format(total_distance, 0) }}</td>
                                <td :class="dangerClass(average_speed)">@{{ number_format(average_speed) }}</td>
                                <td :class="dangerClass(total_sail_time)">@{{ number_format(total_sail_time, 2) }}</td>
                                <td :class="dangerClass(total_loading_time)" style="border-right: 2px solid rgb(72, 79, 91);">@{{ number_format(total_loading_time) }}</td>
                                <td :class="dangerClass(rob_fo)">@{{ number_format(rob_fo) }}</td>
                                <td :class="dangerClass(rob_do)">@{{ number_format(rob_do) }}</td>
                                <td :class="dangerClass(bunker_fo)">@{{ number_format(bunker_fo) }}</td>
                                <td :class="dangerClass(bunker_do)">@{{ number_format(bunker_do) }}</td>
                                <td :class="dangerClass(used_fo)">@{{ number_format(used_fo) }}</td>
                                <td :class="dangerClass(used_do)">@{{ number_format(used_do) }}</td>
                                <td :class="dangerClass(save_fo)">@{{ number_format(save_fo) }}</td>
                                <td :class="dangerClass(save_do)">@{{ number_format(save_do) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                            </table>
                    </form>
                </div>
            </div>
            <!-- Main Contents End -->
        </div>
        <audio controls="controls" class="d-none" id="warning-audio">
            <source src="{{ cAsset('assets/sound/delete.wav') }}">
            <embed src="{{ cAsset('assets/sound/delete.wav') }}" type="audio/wav">
        </audio>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ cAsset('assets/js/bignumber.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue-numeral-filter.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dycombo.js') }}"></script>

	<?php
	echo '<script>';
    echo 'var DynamicStatus = ' . json_encode(g_enum('DynamicStatus')) . ';';
    echo 'var DynamicSub = ' . json_encode(g_enum('DynamicSub')) . ';';
	echo '</script>';
	?>

    <script>
        var searchObj = null;
        var shipId = '{!! $shipId !!}';
        var voyId = '{!! $voyId !!}';
        var shipInfo = '{!! $shipInfo !!}';
        shipInfo=shipInfo.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t");
        shipInfo = JSON.parse(shipInfo);
        var DYNAMIC_SUB_SALING = '{!! DYNAMIC_SUB_SALING !!}';
        var DYNAMIC_SUB_LOADING = '{!! DYNAMIC_SUB_LOADING !!}';
        var DYNAMIC_SUB_DISCH = '{!! DYNAMIC_SUB_DISCH !!}';
        var DYNAMIC_SUB_WAITING = '{!! DYNAMIC_SUB_WAITING !!}';
        var DYNAMIC_SAILING = '{!! DYNAMIC_SAILING !!}';
        var DYNAMIC_CMPLT_DISCH = '{!! DYNAMIC_CMPLT_DISCH !!}';
        const DAY_UNIT = 1000 * 3600;
        var isChangeStatus = false;
        var searchObjTmp = new Array();
        var submitted = false;
        
        $("form").submit(function() {
            submitted = true;
        });

        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                + 'If you leave before saving, your changes will be lost.';

            let currentObj = JSON.parse(JSON.stringify(searchObj.currentData));
            if(JSON.stringify(searchObjTmp) != JSON.stringify(currentObj))
                isChangeStatus = true;
            else
                isChangeStatus = false;

            if (!submitted && isChangeStatus) {
                (e || window.event).returnValue = confirmationMessage;
            }

            return confirmationMessage;
        });

        $(function() {
            initialize();
        });

        function initialize() {
            searchObj = new Vue({
                el: '#search-div',
                data: {
                    shipId: 0,
                    shipName: '',
                    ship_list: [],
                    voy_list: [],
                    port: {
                        loading: '',
                        discharge: '',
                    },
                    activeVoy: 0,

                    prevData: [],
                    currentData: {

                    },

                    dynamicStatus: DynamicStatus,

                    sail_term: {
                        min_date: '0000-00-00',
                        max_date: '0000-00-00',
                    },

                    sail_time:              0,
                    total_distance:         0,
                    total_sail_time:        0,
                    total_loading_time:     0,
                    economic_rate:          0,
                    average_speed:          0,

                    rob_fo:                 0,
                    rob_do:                 0,
                    bunker_fo:              0,
                    bunker_do:              0,

                    used_fo:                0,
                    used_do:                0,
                    save_fo:                0,
                    save_do:                0,
                    total_count:            0,

                    empty:                  true,
                },
                init: function() {
                    this.changeShip();
                },
                methods: {
                    changeShip: function(evt) {
                        location.href = '/business/dynRecord?shipId=' + $(evt.target).val();
                    },
                    getShipName: function(shipName, EnName) {
                        return shipName == '' ? EnName : shipName;
                    },
                    getVoyList: function(shipId) {
                        $.ajax({
                            url: BASE_URL + 'ajax/business/voy/list',
                            type: 'post',
                            data: {
                                shipId: shipId,
                            },
                            success: function(result) {
                                searchObj.voy_list = [];
                                searchObj.voy_list = Object.assign([], [], result);
                            }
                        });
                    },
                    number_format: function(value, decimal = 1) {
                        return __parseFloat(value) == 0 ? '-' : number_format(value, decimal);
                    },
                    dangerClass: function(value) {
                        return isNaN(value) || value < 0 ? 'text-danger' : '';
                    },
                    onChangeVoy: function(evt) {
                        var confirmationMessage = 'It looks like you have been editing something. '
                                + 'If you leave before saving, your changes will be lost.';
                        let currentObj = JSON.parse(JSON.stringify(searchObj.currentData));
                        if(JSON.stringify(searchObjTmp) != JSON.stringify(currentObj))
                            isChangeStatus = true;
                        else
                            isChangeStatus = false;

                        if (!submitted && isChangeStatus) {
                            bootbox.confirm(confirmationMessage, function (result) {
                                if (!result) {
                                    return;
                                }
                                else {
                                    searchObj.setPortName();
                                    searchObj.getData();
                                }
                            });
                        } else {
                            this.setPortName();
                            this.getData();
                        }

                    },
                    getData: function() {
                        $.ajax({
                            url: BASE_URL + 'ajax/business/dynamic/list',
                            type: 'post',
                            data: {
                                shipId: searchObj.shipId,
                                voyId: searchObj.activeVoy
                            },
                            success: function(result) {
                                let data = result;
                                searchObj.currentData = [];
                                searchObj.prevData = [];
                                if(data['prevData'] != undefined && data['prevData'] != null) {
                                    searchObj.prevData = Object.assign([], [], data['prevData']);
                                    searchObj.prevData['Voy_Type'] = DynamicSub[searchObj.prevData['Voy_Type']];
                                    searchObj.prevData['Voy_Status'] = DynamicStatus[searchObj.prevData['Voy_Status']][0];
                                    if(searchObj.prevData['Voy_Hour'] < 10)
                                            searchObj.prevData['Voy_Hour'] = "0" + searchObj.prevData['Voy_Hour'];

                                    if(searchObj.prevData['Voy_Minute'] < 10)
                                        searchObj.prevData['Voy_Minute'] = "0" + searchObj.prevData['Voy_Minute'];

                                    searchObj.prevData['Cargo_Qtty'] = __parseFloat(searchObj.prevData['Cargo_Qtty']).toFixed(0);
                                }
                                
                                if(data['currentData'] != undefined && data['currentData'] != null && data['currentData'].length > 0) {
                                    searchObj.currentData = Object.assign([], [], data['currentData']);

                                    let total_sail_time = 0;
                                    let total_loading_time = 0;
                                    let total_waiting_time = 0;

                                    searchObj.setTotalDefault();
                                    searchObj.setTotalInfo(data);
                                    searchObj.currentData.forEach(function(value, key) {
                                        searchObj.currentData[key]['dynamicSub'] = getSubList(value['Voy_Status']);
                                        value['Sail_Distance'] = __parseFloat(value['Sail_Distance']);
                                        value['Speed'] = __parseFloat(value['Speed']);
                                        value['RPM'] = __parseFloat(value['RPM']);
                                        value['ROB_FO'] = __parseFloat(value['ROB_FO']);
                                        value['ROB_DO'] = __parseFloat(value['ROB_DO']);
                                        value['BUNK_FO'] = __parseFloat(value['BUNK_FO']);
                                        value['BUNK_DO'] = __parseFloat(value['BUNK_DO']);

                                        searchObj.total_distance += parseInt(value["Sail_Distance"]);

                                        searchObj.bunker_fo += value['BUNK_FO'];
                                        searchObj.bunker_do += value['BUNK_DO'];

                                        searchObj.currentData[key]['Sail_Distance'] = parseFloat(value['Sail_Distance']) == 0 ? '' : value['Sail_Distance'];
                                        searchObj.currentData[key]['Speed'] = parseFloat(value['Speed']) == 0 ? '' : value['Speed'];
                                        if(value['Voy_Status'] == DYNAMIC_CMPLT_DISCH)
                                            searchObj.currentData[key]['Cargo_Qtty'] = parseFloat(value['Cargo_Qtty']).toFixed(0);
                                        else
                                            searchObj.currentData[key]['Cargo_Qtty'] = __parseFloat(value['Cargo_Qtty']) == 0 ? '' : __parseFloat(value['Cargo_Qtty']);

                                        searchObj.currentData[key]['RPM'] = parseFloat(value['RPM']) == 0 ? '' : value['RPM'];
                                        searchObj.currentData[key]['ROB_FO'] = parseFloat(value['ROB_FO']) == 0 ? '' : value['ROB_FO'];
                                        searchObj.currentData[key]['ROB_DO'] = parseFloat(value['ROB_DO']) == 0 ? '' : value['ROB_DO'];
                                        searchObj.currentData[key]['BUNK_FO'] = parseFloat(value['BUNK_FO']) == 0 ? '' : value['BUNK_FO'];
                                        searchObj.currentData[key]['BUNK_DO'] = parseFloat(value['BUNK_DO']) == 0 ? '' : value['BUNK_DO'];

                                        if(key > 0) {
                                            // Calc Sail Count
                                            if(value['Voy_Type'] == DYNAMIC_SUB_SALING) {
                                                let preKey = key - 1;
                                                let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'];
                                                let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'];
                                                total_sail_time += __getTermDay(start_date, end_date, searchObj.currentData[preKey]['GMT'], value['GMT']);
                                            }
                                            // Calc Sail Count
                                            if(value['Voy_Type'] == DYNAMIC_SUB_LOADING || value['Voy_Type'] == DYNAMIC_SUB_DISCH) {
                                                let preKey = key - 1;
                                                let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'];
                                                let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'];
                                                total_loading_time += __getTermDay(start_date, end_date, searchObj.currentData[preKey]['GMT'], value['GMT']);
                                            }

                                            if(value['Voy_Type'] == DYNAMIC_SUB_WAITING) {
                                                let preKey = key - 1;
                                                let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'];
                                                let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'];
                                                total_waiting_time += __getTermDay(start_date, end_date, searchObj.currentData[preKey]['GMT'], value['GMT']);
                                            }
                                        }

                                        if(searchObj.currentData[key]['Voy_Hour'] < 10)
                                            searchObj.currentData[key]['Voy_Hour'] = "0" + searchObj.currentData[key]['Voy_Hour'];

                                        if(searchObj.currentData[key]['Voy_Minute'] < 10)
                                            searchObj.currentData[key]['Voy_Minute'] = "0" + searchObj.currentData[key]['Voy_Minute'];
                                        
                                    });

                                    
                                    searchObj.total_sail_time = total_sail_time.toFixed(2);
                                    searchObj.total_loading_time = total_loading_time.toFixed(2);
                                    searchObj.average_speed = BigNumber(searchObj.total_distance).div(searchObj.total_sail_time).div(24).toFixed(1);
                                    
                                    searchObj.economic_rate = BigNumber(total_loading_time).plus(searchObj.total_sail_time).div(searchObj.sail_time).multipliedBy(100).toFixed(1);
                                    searchObj.prevData['ROB_FO'] = searchObj.prevData['ROB_FO'] == null || searchObj.prevData['ROB_FO'] == undefined ? 0 : searchObj.prevData['ROB_FO'];
                                    searchObj.prevData['ROB_DO'] = searchObj.prevData['ROB_DO'] == null || searchObj.prevData['ROB_DO'] == undefined ? 0 : searchObj.prevData['ROB_DO'];
                                    searchObj.rob_fo = BigNumber(searchObj.prevData['ROB_FO']).plus(searchObj.bunker_fo).minus(__parseFloat(data['max_date']['ROB_FO'])).toFixed(1);
                                    searchObj.rob_do = BigNumber(searchObj.prevData['ROB_DO']).plus(searchObj.bunker_do).minus(data['max_date']['ROB_DO']).toFixed(1);

                                    let usedFoTmp1 = BigNumber(searchObj.total_sail_time).multipliedBy(shipInfo['FOSailCons_S']);
                                    let usedFoTmp2 = BigNumber(searchObj.total_loading_time).multipliedBy(shipInfo['FOL/DCons_S']);
                                    let usedFoTmp3 = BigNumber(total_waiting_time).multipliedBy(shipInfo['FOIdleCons_S']);

                                    let usedDoTmp1 = BigNumber(searchObj.total_sail_time).multipliedBy(shipInfo['DOSailCons_S']);
                                    let usedDoTmp2 = BigNumber(searchObj.total_loading_time).multipliedBy(shipInfo['DOL/DCons_S']);
                                    let usedDoTmp3 = BigNumber(total_waiting_time).multipliedBy(shipInfo['DOIdleCons_S']);

                                    searchObj.used_fo = BigNumber(usedFoTmp1).plus(usedFoTmp2).plus(usedFoTmp3).toFixed(2);
                                    searchObj.used_do = BigNumber(usedDoTmp1).plus(usedDoTmp2).plus(usedDoTmp3).toFixed(2);

                                    // tmp
                                    searchObj.save_fo = BigNumber(searchObj.used_fo).minus(searchObj.rob_fo).toFixed(2);
                                    searchObj.save_do = BigNumber(searchObj.used_do).minus(searchObj.rob_do).toFixed(2);

                                } else {
                                    searchObj.setDefaultData();
                                }

                                searchObj.total_count = searchObj.currentData.length;

                                searchObjTmp = JSON.parse(JSON.stringify(searchObj.currentData));

                            }
                        })
                    },
                    setTotalInfo: function(data) {
                        searchObj.sail_term['min_date'] = data['min_date'] == false ? '' : data['min_date']['Voy_Date'];
                        searchObj.sail_term['max_date'] = data['max_date'] == false ? '' : data['max_date']['Voy_Date'];
                        let start_date = data['min_date']['Voy_Date'] + ' ' + data['min_date']['Voy_Hour'] + ':' + data['min_date']['Voy_Minute'];
                        let end_date = data['max_date']['Voy_Date'] + ' ' + data['max_date']['Voy_Hour'] + ':' + data['max_date']['Voy_Minute'];
                        this.sail_time = __getTermDay(start_date, end_date, data['min_date']['GMT'], data['max_date']['GMT']);
                    },
                    setTotalDefault: function() {
                        this.sail_time = 0;
                        this.total_distance = 0;
                        this.total_sail_time = 0;
                        this.total_loading_time = 0;
                        this.economic_rate = 0;
                        this.average_speed = 0;

                        this.rob_fo = 0;
                        this.rob_do = 0;
                        this.bunker_fo = 0;
                        this.bunker_do = 0;

                        this.used_fo = 0;
                        this.used_do = 0;
                        this.save_fo = 0;
                        this.save_do = 0;
                        this.total_count = 0;
                    },
                    setPortName: function() {
                        searchObj.voy_list.forEach(function(value, index) {
                            if(searchObj.activeVoy == value['Voy_No']) {
                                searchObj.port['loading'] = value['LPort'] == false ? '-' : value['LPort'];
                                searchObj.port['discharge'] = value['DPort'] == false ? '-' : value['DPort'];
                                status = 1;
                            }
                        });
                    },
                    dateModify(e, index) {
                        $(e.target).on("change", function() {
                            searchObj.currentData[index]['Voy_Date'] = $(this).val();
                        });
                    },
                    onChangeStatus: function(e, index) {
                        let voyStatus = $(e.target).val();
                        searchObj.currentData[index]['dynamicSub'] = getSubList(voyStatus);
                        searchObj.currentData[index]['Voy_Type'] = getSubList(voyStatus)[0][0];
                        searchObj.$forceUpdate();
                    },
                    submitForm: function() {
                        submitted = true;
                        if(this.validateForm() == -2) {
                            alert('Please input ROB/FO, ROB/DO value.');
                            return;
                        } else if(this.validateForm() == -1) {
                            alert('"CGO QTY" is require input field.');
                            return;
                        } else 
                            $('#dynamic-form').submit();
                    },
                    validateForm() {
                        let $this = this.currentData;
                        var retVal = true;
                        $this.forEach(function(value, key) {
                            if(value['Cargo_Qtty'] == '')
                                $this[key]['Cargo_Qtty'] = null;
                        });
                        $this.forEach(function(value, key) {
                            if($this[key]['Voy_Status'] == DYNAMIC_CMPLT_DISCH) {
                                if(value['Cargo_Qtty'] == undefined || value['Cargo_Qtty'] == null) {
                                    retVal = -1;
                                } else if(value['Cargo_Qtty'] == 0) {
                                    if(__parseFloat(value['ROB_FO']) == 0 || __parseFloat(value['ROB_DO']) == 0) {
                                        retVal = -2;
                                    }
                                }
                            }
                        });
                        
                        return retVal;

                    },
                    getToday: function(symbol) {
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = yyyy + symbol + mm + symbol + dd;

                        return today;
                    },
                    addRow: function(index) {
                        let length = this.currentData.length;
                        if(length != 0 && length - 1 != index)
                            return;
                            
                        this.setDefaultData();
                    },
                    setDefaultData() {
                        let length = searchObj.currentData.length;
                        searchObj.currentData.push([]);
                        searchObj.currentData[length]['Voy_Status'] = DYNAMIC_SAILING;
                        searchObj.currentData[length]['dynamicSub'] = getSubList(DYNAMIC_SAILING);
                        searchObj.currentData[length]['Voy_Type'] = DYNAMIC_SUB_SALING;
                        searchObj.currentData[length]['Voy_Hour'] = "08";
                        searchObj.currentData[length]['Voy_Minute'] = "00";
                        if(length > 0) {
                            searchObj.currentData[length]['Voy_Date'] = searchObj.currentData[length - 1]['Voy_Date'];
                            searchObj.currentData[length]['GMT'] = searchObj.currentData[length - 1]['GMT'];
                        } else {
                            searchObj.currentData[length]['Voy_Date'] = this.getToday('-');
                            searchObj.currentData[length]['GMT'] = 8;
                        }

                        searchObj.$forceUpdate();
                    },
                    limitHour: function(e, index) {
                        let val = e.target.value;
                        if(val > 25)
                            this.currentData[index]['Voy_Hour'] = 23;
                        if(val < 0)
                            this.currentData[index]['Voy_Hour'] = 0;
                    },
                    limitMinute: function(e, index) {
                        let val = e.target.value;
                        if(val > 60)
                            this.currentData[index]['Voy_Minute'] = 59;
                        if(val < 0)
                            this.currentData[index]['Voy_Minute'] = 0;
                    },
                    limitGMT: function(e, index) {
                        let val = e.target.value;
                        if(val > 10)
                            this.currentData[index]['GMT'] = 9;
                        if(val < 0)
                            this.currentData[index]['GMT'] = 0;
                    },
                    deleteItem: function(id, index) {
                        let length = this.currentData.length;
                        let _this = this;
                        if(length == 0 && this.empty) {
                            return;
                        }
                        __alertAudio();
                        bootbox.confirm("Are you sure you want to delete?", function (result) {
                            if (result) {
                                if (id != undefined) {
                                $.ajax({
                                    url: BASE_URL + 'ajax/business/dynrecord/delete',
                                    type: 'post',
                                    data: {
                                        id: id,
                                    },
                                    success: function (data, status, xhr) {
                                        searchObj.currentData.splice(index, 1);
                                        if(_this.currentData.length == 0) {
                                            _this.empty = true;
                                            _this.setDefaultData();
                                        }
                                        searchObjTmp = JSON.parse(JSON.stringify(_this.currentData));
                                    }
                                });
                                } else {
                                    searchObj.currentData.splice(index, 1);
                                    if(_this.currentData.length == 0) {
                                        _this.empty = true;
                                        _this.setDefaultData();
                                    }
                                    searchObjTmp = JSON.parse(JSON.stringify(_this.currentData));
                                }
                            }
                        });
                    }
                },
                computed: {
                    deleteClass: function() {
                        let length = this.currentData.length;
                        let _this = this;
                        this.currentData.map(function(data) {
                            if(data.id != undefined)
                                _this.empty = false;
                        });

                        return _this.empty && length == 1 ? 'd-none' : '';
                    }
                },
                updated() {
                    $('.date-picker').datepicker({
                        autoclose: true,
                    }).next().on(ace.click_event, function () {
                        $(this).prev().focus();
                    });

                    $('.hour-input').on('blur keyup', function() {
                        let val = $(this).val();
                        if(val > 25)
                            $(this).val(23);
                        if(val < 0)
                            $(this).val(0);
                    });

                    $('.minute-input').on('blur keyup', function() {
                        let val = $(this).val();
                        if(val > 60)
                            $(this).val(59);
                        if(val < 0)
                            $(this).val(0);
                    });

                    $('.gmt-input').on('blur keyup', function() {
                        let val = $(this).val();
                        if(val > 10)
                            $(this).val(9);
                        if(val < 0)
                            $(this).val(0);
                    });


                }
            });


            if(voyId != '')
                searchObj.activeVoy = voyId;

            searchObj.shipId = shipId;
            
            getInitInfo();
        }

        function getInitInfo() {
            $.ajax({
                url: BASE_URL + 'ajax/business/voy/list',
                type: 'post',
                data: {
                    shipId: shipId,
                },
                success: function(result) {
                    searchObj.voy_list = [];
                    searchObj.voy_list = Object.assign([], [], result);
                    if(searchObj.voy_list.length > 0) {
                        searchObj.activeVoy = voyId != '' ? voyId : searchObj.voy_list[0]['Voy_No'];
                    }

                    searchObj.setPortName();
                    searchObj.getData();
                }
            });
        }

        function getSubList(type) {
            let tmp = DynamicStatus[type][1];
            let retVal = [];
            tmp.forEach(function(value) {
                retVal.push([value, DynamicSub[value]]);
            });

            return retVal;
        }

        function __getTermDay(start_date, end_date, start_gmt = 8, end_gmt = 8) {
            let currentDate = moment(end_date).valueOf();
            let currentGMT = DAY_UNIT * end_gmt;
            let prevDate = moment(start_date).valueOf();
            let prevGMT = DAY_UNIT * start_gmt;
            let diffDay = 0;
            currentDate = BigNumber(currentDate).minus(currentGMT).div(DAY_UNIT);
            prevDate = BigNumber(prevDate).minus(prevGMT).div(DAY_UNIT);
            diffDay = currentDate.minus(prevDate);

            return parseFloat(diffDay.div(24));
        }

        $('body').on('keydown', 'input, select', function(e) {
            if (e.key === "Enter") {
                var self = $(this), form, focusable, next;
                form = $('#dynamic-form');
            
                focusable = form.find('input,a,select,textarea').filter(':visible');
                next = focusable.eq(focusable.index(this)+1);
                if (next.length) {
                    next.focus();
                }
                return false;
            }
        });
    </script>

@endsection