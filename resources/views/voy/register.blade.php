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
        <div class="page-content" id="search-div">
            <div class="row pt-2" v-cloak>
                <div class="col-sm-12 full-width">
                    <select class="custom-select d-inline-block" style="padding: 4px;max-width: 100px;" @change="changeShip" v-model="shipId">
                        @foreach($shipList as $ship)
                            <option value="{{ $ship['IMO_No'] }}"
                                    {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                            </option>
                        @endforeach
                    </select>
                    <select class="text-center" style="width: 60px;" name="voy_list" @change="onChangeVoy" v-model="activeVoy">
                        <template v-for="voyItem in voy_list">
                            <option :value="voyItem.Voy_No">@{{ voyItem.Voy_No }}</option>
                        </template>
                    </select>
                    <strong style="font-size: 16px; padding: 6px 0 0 16px;">
                        <span class="font-bold">动态记录</span>
                    </strong>
                    <div class="btn-group f-right">
                        <a class="btn btn-primary btn-sm search-btn" role="button" data-toggle="modal" @click="addItem"><i class="icon-plus"></i>添加</a>
                    </div>
                </div>
            </div>

            <!-- Main Contents Begin -->
            <div class="row col-lg-12" style="margin-top: 4px;">
                <div class="head-fix-div common-list">

                    <input type="hidden" name="_CP_ID" v-model="activeVoy">
                    <table class="table-bordered dynamic-table table-striped" v-cloak>
                        <thead>
                        <tr>
                            <th class="text-center font-style-italic" style="width: 40px; height: 25px;">VOY No</th>
                            <th class="text-center font-style-italic">DATE</th>
                            <th class="text-center font-style-italic">LT</th>
                            <th class="text-center font-style-italic" style="width: 150px;">STATUS</th>
                            <th class="text-center font-style-italic" style="width: 160px;">POSITION</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="prev-voy">
                            <td class="text-center">@{{ prevData['CP_ID'] }}</td>
                            <td class="text-center">@{{ prevData['Voy_Date'] }}</td>
                            <td class="text-center">@{{ prevData['GMT'] }}</td>
                            <td style="padding-left: 8px!important;">@{{ prevData['Voy_Status'] }}</td>
                            <td style="padding-left: 4px!important">@{{ prevData['Ship_Position'] }}</td>
                        </tr>
                        <template v-for="(currentItem, index) in currentData">
                            <tr class="dynamic-item">
                                <td class="d-none"><input type="hidden" :value="currentItem.id" name="id[]"></td>
                                <td class="text-center voy-no" style="background:linear-gradient(#fff, #d9f8fb)!important;">@{{ activeVoy }}</td>
                                <td class="text-center date-width"><input type="text" class="date-picker form-control text-center" name="Voy_Date[]" v-model="currentItem.Voy_Date" @click="dateModify($event, index)" data-date-format="yyyy-mm-dd"></td>
                                <td class="time-width"><input type="number" class="form-control text-center gmt-input" name="GMT[]" v-model="currentItem.GMT" @blur="limitGMT($event, index)" @keyup="limitGMT($event, index)"></td>
                                <td>
                                    <select type="number" class="form-control" name="Voy_Status[]" v-model="currentItem.Voy_Status" @change="onChangeStatus($event, index)">
                                        <option v-for="(item, index) in dynamicStatus" v-bind:value="index">@{{ item[0] }}</option>
                                    </select>
                                </td>
                                <td class="position-width"><input type="text" maxlength="25" class="form-control" name="Ship_Position[]" v-model="currentItem.Ship_Position" autocomplete="off"></td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Main Contents End -->

            <!-- Register Modal Show -->
            <div id="modal-wizard" class="modal modal-draggable" aria-hidden="true">
                <div class="dynamic-modal-dialog">
                    <div class="dynamic-modal-content" style="border: 0;width:100%!important;">
                        <div class="dynamic-modal-header" data-target="#modal-step-contents">
                            <div class="table-header">
                                <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                    <span class="white">&times;</span>
                                </button>
                                <h4 style="padding-top:10px;">动态记录 (VOY @{{ activeVoy }})</h4>
                            </div>
                        </div>
                        <div id="modal-body-content" class="modal-body step-content">
                            <div class="row">
                                <form action="{{ route('voy.update') }}" method="post" id="dynamic-form" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="shipId" value="{{ $shipId }}">
                                    <input type="hidden" name="id" v-model="currentItem.id">
                                    <input type="hidden" name="CP_ID" v-model="activeVoy">
                                    <table class="register-voy">
                                        <tbody>
                                        <tr>
                                            <td>DATE</td>
                                            <td colspan="3">
                                                <input type="text" class="date-picker form-control text-center" name="Voy_Date" v-model="currentItem.Voy_Date" @click="dateModify($event)" data-date-format="yyyy-mm-dd">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>TIME(LT)<span class="text-danger">*</span></td>
                                            <td class="time-width">
                                                <input type="number" class="form-control text-center hour-input" required name="Voy_Hour" v-model="currentItem.Voy_Hour" @blur="limitHour($event)" @keyup="limitHour($event)">
                                            </td>
                                            <td class="time-width">
                                                <input type="number" class="form-control text-center minute-input" required name="Voy_Minute" v-model="currentItem.Voy_Minute" @blur="limitMinute($event)" @keyup="limitMinute($event)">
                                            </td>
                                            <td class="time-width">(hh:mm)</td>
                                        </tr>
                                        <tr>
                                            <td>GMT<span class="text-danger">*</span></td>
                                            <td class="time-width"><input type="number" required class="form-control text-center gmt-input" name="GMT" v-model="currentItem.GMT" @blur="limitGMT($event)" @keyup="limitGMT($event)"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td>STATUS<span class="text-danger">*</span></td>
                                            <td colspan="3">
                                                <select type="number" class="form-control" name="Voy_Status" v-model="currentItem.Voy_Status" @change="onChangeStatus($event)" required>
                                                    <option v-for="(item, index) in dynamicStatus" v-bind:value="index">@{{ item[0] }}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-style-normal">种类</td>
                                            <td colspan="3">
                                                <select type="number" class="form-control" name="Voy_Type" v-model="currentItem.Voy_Type">
                                                    <option v-for="(item, index) in currentItem.dynamicSub" v-bind:value="item[0]">@{{ item[1] }}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>POSITION</td>
                                            <td colspan="3">
                                                <input type="text" maxlength="25" class="form-control" name="Ship_Position" v-model="currentItem.Ship_Position" autocomplete="off">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>DTG</td>
                                            <td colspan="2"><input type="number" max="100000" class="form-control text-center"  :readonly="currentItem.Voy_Status != DYNAMIC_DEPARTURE"  name="Sail_Distance" v-model="currentItem.Sail_Distance"></td>
                                            <td>N.Mile</td>
                                        </tr>
                                        <tr>
                                            <td>SPEED</td>
                                            <td colspan="2"><input type="number" class="form-control text-center" name="Speed" v-model="currentItem.Speed"></td>
                                            <td>Kn</td>
                                        </tr>

                                        <tr>
                                            <td>RPM</td>
                                            <td colspan="2"><input type="number" class="form-control text-center" name="RPM" v-model="currentItem.RPM"></td>
                                            <td>rpm</td>
                                        </tr>

                                        <tr>
                                            <td>CGO QTY(MT)<span class="text-danger">*</span></td>
                                            <td colspan="3">
                                                <input type="number" class="form-control text-right font-weight-bold" :style="currentItem.Voy_Status == '13' ? 'color: red!important' : ''" name="Cargo_Qtty" v-model="currentItem.Cargo_Qtty" required>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>ROB(FO)<span class="text-danger">*</span></td>
                                            <td colspan="2">
                                                <input type="number" class="form-control text-center" style="padding: 0!important" :style="currentItem.Voy_Status == '13' ? 'color: red!important' : ''" name="ROB_FO" v-model="currentItem.ROB_FO" required>
                                            </td>
                                            <td>MT</td>
                                        </tr>
                                        <tr>
                                            <td>ROB(DO)<span class="text-danger">*</span></td>
                                            <td colspan="2">
                                                <input type="number" class="form-control text-center" style="padding: 0!important" :style="currentItem.Voy_Status == '13' ? 'color: red!important' : ''" name="ROB_DO" v-model="currentItem.ROB_DO" required>
                                            </td>
                                            <td>MT</td>
                                        </tr>
                                        <tr>
                                            <td>BUNKERING(FO)</td>
                                            <td colspan="2">
                                                <input type="number" class="form-control text-center" name="BUNK_FO"  style="color: blue!important; padding: 0!important;" v-model="currentItem.BUNK_FO">
                                            </td>
                                            <td>MT</td>
                                        </tr>
                                        <tr>
                                            <td>BUNKERING(DO)</td>
                                            <td colspan="2">
                                                <input type="number" class="form-control text-center" name="BUNK_DO"  style="color: blue!important; padding: 0!important;" v-model="currentItem.BUNK_DO">
                                            </td>
                                            <td>MT</td>
                                        </tr>
                                        <tr>
                                            <td>REMARK</td>
                                            <td class="position-width" colspan="3"><textarea class="form-control" name="Remark" rows="1" style="resize: none" maxlength="50" autocomplete="off" v-model="currentItem.Remark"></textarea></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="btn-group f-right mt-20 d-flex">
                                        <button type="submit" class="btn btn-success small-btn ml-0">
                                            <i class="icon-save"></i>保存
                                        </button>
                                        <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>删除</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ cAsset('assets/js/bignumber.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="{{ cAsset('assets/js/sprintf.min.js') }}"></script>
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
        shipInfo = shipInfo.replaceAll(/\n/g, "\\n").replaceAll(/\r/g, "\\r").replaceAll(/\t/g, "\\t");
        shipInfo = JSON.parse(shipInfo);
        var DYNAMIC_SUB_SALING = '{!! DYNAMIC_SUB_SALING !!}';
        var DYNAMIC_DEPARTURE = '{!! DYNAMIC_DEPARTURE !!}';
        var DYNAMIC_SUB_LOADING = '{!! DYNAMIC_SUB_LOADING !!}';
        var DYNAMIC_SUB_DISCH = '{!! DYNAMIC_SUB_DISCH !!}';
        var DYNAMIC_SUB_WAITING = '{!! DYNAMIC_SUB_WAITING !!}';
        var DYNAMIC_SUB_WEATHER = '{!! DYNAMIC_SUB_WEATHER !!}';
        var DYNAMIC_SUB_REPAIR = '{!! DYNAMIC_SUB_REPAIR !!}';
        var DYNAMIC_SUB_SUPPLY = '{!! DYNAMIC_SUB_SUPPLY !!}';
        var DYNAMIC_SUB_ELSE = '{!! DYNAMIC_SUB_ELSE !!}';

        var DYNAMIC_SAILING = '{!! DYNAMIC_SAILING !!}';
        var DYNAMIC_CMPLT_DISCH = '{!! DYNAMIC_CMPLT_DISCH !!}';
        var DYNAMIC_CMPLT_LOADING = '{!! DYNAMIC_CMPLT_LOADING !!}';
        const DAY_UNIT = 1000 * 3600;
        var isChangeStatus = false;
        var searchObjTmp = new Array();
        var submitted = false;
        var tmp;

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

                    currentItem: [],
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
                    onChangeVoy(evt) {
                        var newVal = this.activeVoy;
                        var confirmationMessage = 'It looks like you have been editing something. '
                            + 'If you leave before saving, your changes will be lost.';
                        let currentObj = JSON.parse(JSON.stringify(searchObj.currentData));
                        if(JSON.stringify(searchObjTmp) != JSON.stringify(currentObj))
                            isChangeStatus = true;
                        else
                            isChangeStatus = false;

                        if (!submitted && isChangeStatus) {
                            __alertAudio();
                            this.activeVoy = tmp;
                            bootbox.confirm(confirmationMessage, function (result) {
                                if (!result) {
                                    return;
                                }
                                else {
                                    searchObj.activeVoy = newVal;
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
                                    searchObj.sail_term['min_date']= searchObj.currentData[0]['Voy_Date'];
                                    let tmpData = searchObj.currentData;
                                    searchObj.sail_term['max_date'] = tmpData[tmpData.length - 1]['Voy_Date'];
                                    let total_sail_time = 0;
                                    let total_loading_time = 0;
                                    let loading_time = 0;
                                    let disch_time = 0;
                                    let total_waiting_time = 0;
                                    let total_weather_time = 0;
                                    let total_repair_time = 0;
                                    let total_supply_time = 0;
                                    let total_else_time = 0;
                                    let total_distance = 0;

                                    searchObj.rob_fo = 0;
                                    searchObj.rob_do = 0;
                                    searchObj.bunker_fo = 0;
                                    searchObj.bunker_do = 0;
                                    searchObj.used_fo = 0;
                                    searchObj.used_fo = 0;
                                    searchObj.save_fo = 0;
                                    searchObj.save_do = 0;
                                    searchObj.total_distance = 0;
                                    searchObj.average_speed = 0;

                                    var start_date = searchObj.prevData['Voy_Date'] + ' ' + searchObj.prevData['Voy_Hour'] + ':' + searchObj.prevData['Voy_Minute'] + ':00';
                                    var start_gmt = searchObj.prevData['GMT'];
                                    searchObj.currentData.forEach(function(value, key) {
                                        searchObj.currentData[key]['dynamicSub'] = getSubList(value['Voy_Status']);
                                        searchObj.currentData[key]['Voy_Status_Name'] = DynamicStatus[value['Voy_Status']][0];
                                        searchObj.currentData[key]['Voy_Type_Name'] = DynamicSub[value['Voy_Type']];
                                        searchObj.total_distance += __parseFloat(value["Sail_Distance"]);
                                        searchObj.bunker_fo += __parseFloat(value['BUNK_FO']);
                                        searchObj.bunker_do += __parseFloat(value['BUNK_DO']);
                                        searchObj.rob_fo += __parseFloat(value['ROB_FO']);
                                        searchObj.rob_do += __parseFloat(value['ROB_DO']);

                                        // if(key > 0) {
                                        // Calc Sail Count
                                        if(value['Voy_Type'] == DYNAMIC_SUB_SALING) {
                                            let preKey = key - 1;
                                            // let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'] + ':00';
                                            let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                            total_sail_time += __getTermDay(start_date, end_date, start_gmt, value['GMT']);
                                        }
                                        // Calc Sail Count
                                        if(value['Voy_Type'] == DYNAMIC_SUB_LOADING ) {
                                            let preKey = key - 1;
                                            // let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'] + ':00';
                                            let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                            loading_time += __getTermDay(start_date, end_date, start_gmt, value['GMT']);
                                        }

                                        if(value['Voy_Type'] == DYNAMIC_SUB_DISCH) {
                                            let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                            disch_time += __getTermDay(start_date, end_date, start_gmt, value['GMT']);
                                        }

                                        if(value['Voy_Type'] == DYNAMIC_SUB_WAITING) {
                                            let preKey = key - 1;
                                            // let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'] + ':00';
                                            let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                            total_waiting_time += __getTermDay(start_date, end_date, start_gmt, value['GMT']);
                                        }

                                        if(value['Voy_Type'] == DYNAMIC_SUB_WEATHER) {
                                            let preKey = key - 1;
                                            // let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'] + ':00';
                                            let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                            total_weather_time += __getTermDay(start_date, end_date, start_gmt, value['GMT']);
                                        }

                                        if(value['Voy_Type'] == DYNAMIC_SUB_REPAIR) {
                                            let preKey = key - 1;
                                            // let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'] + ':00';
                                            let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                            total_repair_time += __getTermDay(start_date, end_date, start_gmt, value['GMT']);
                                        }

                                        if(value['Voy_Type'] == DYNAMIC_SUB_SUPPLY) {
                                            let preKey = key - 1;
                                            // let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'] + ':00';
                                            let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                            total_supply_time += __getTermDay(start_date, end_date, start_gmt, value['GMT']);
                                        }

                                        if(value['Voy_Type'] == DYNAMIC_SUB_ELSE) {
                                            let preKey = key - 1;
                                            // let start_date = searchObj.currentData[preKey]['Voy_Date'] + ' ' + searchObj.currentData[preKey]['Voy_Hour'] + ':' + searchObj.currentData[preKey]['Voy_Minute'] + ':00';
                                            let end_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                            total_else_time += __getTermDay(start_date, end_date, start_gmt, value['GMT']);
                                        }
                                        // }

                                        start_date = value['Voy_Date'] + ' ' + value['Voy_Hour'] + ':' + value['Voy_Minute'] + ':00';
                                        start_gmt = value['GMT'];
                                        if(searchObj.currentData[key]['Voy_Hour'] < 10)
                                            searchObj.currentData[key]['Voy_Hour'] = "0" + searchObj.currentData[key]['Voy_Hour'];

                                        if(searchObj.currentData[key]['Voy_Minute'] < 10)
                                            searchObj.currentData[key]['Voy_Minute'] = "0" + searchObj.currentData[key]['Voy_Minute'];
                                    });

                                    searchObj.total_sail_time = total_sail_time.toFixed(2);
                                    searchObj.total_loading_time = BigNumber(loading_time.toFixed(2)).plus(disch_time.toFixed(2)).toFixed(2);

                                    searchObj.average_speed = BigNumber(searchObj.total_distance).div(searchObj.total_sail_time).div(24).toFixed(1);

                                    // searchObj.economic_rate = BigNumber(total_loading_time).plus(searchObj.total_sail_time).div(searchObj.sail_time).multipliedBy(100).toFixed(1);
                                    searchObj.prevData['ROB_FO'] = __parseFloat(searchObj.prevData['ROB_FO']);
                                    searchObj.prevData['ROB_DO'] = __parseFloat(searchObj.prevData['ROB_DO']);
                                    data['max_date']['ROB_FO'] = __parseFloat(data['max_date']['ROB_FO']);
                                    data['max_date']['ROB_DO'] = __parseFloat(data['max_date']['ROB_DO']);
                                    searchObj.rob_fo = BigNumber(searchObj.prevData['ROB_FO']).plus(searchObj.bunker_fo).minus(data['max_date']['ROB_FO']).toFixed(2);
                                    searchObj.rob_do = BigNumber(searchObj.prevData['ROB_DO']).plus(searchObj.bunker_do).minus(data['max_date']['ROB_DO']).toFixed(2);

                                    let loadTmp = BigNumber(__parseFloat(loading_time.toFixed(2))).plus(__parseFloat(disch_time.toFixed(2))).plus(__parseFloat(total_sail_time.toFixed(2)));
                                    let non_economic_date = BigNumber(__parseFloat(total_waiting_time.toFixed(2))).plus(__parseFloat(total_weather_time.toFixed(2))).plus(__parseFloat(total_repair_time.toFixed(2))).plus(__parseFloat(total_supply_time.toFixed(2))).plus(__parseFloat(total_else_time.toFixed(2))).toFixed(2)

                                    searchObj.sail_time = __parseFloat(non_economic_date) + __parseFloat(loadTmp);
                                    searchObj.economic_rate = BigNumber(loadTmp).div(__parseFloat(searchObj.sail_time.toFixed(2))).multipliedBy(100).toFixed(1);


                                    let usedFoTmp1 = BigNumber(searchObj.total_sail_time).multipliedBy(shipInfo['FOSailCons_S']);
                                    let usedFoTmp2 = BigNumber(loading_time).plus(disch_time).multipliedBy(shipInfo['FOL/DCons_S']);
                                    let usedFoTmp3 = BigNumber(non_economic_date).multipliedBy(shipInfo['FOIdleCons_S']);

                                    let usedDoTmp1 = BigNumber(searchObj.total_sail_time).multipliedBy(shipInfo['DOSailCons_S']);
                                    let usedDoTmp2 = BigNumber(loading_time).plus(disch_time).multipliedBy(shipInfo['DOL/DCons_S']);
                                    let usedDoTmp3 = BigNumber(non_economic_date).multipliedBy(shipInfo['DOIdleCons_S']);

                                    searchObj.used_fo = BigNumber(usedFoTmp1).plus(usedFoTmp2).plus(usedFoTmp3).toFixed(2);
                                    searchObj.used_do = BigNumber(usedDoTmp1).plus(usedDoTmp2).plus(usedDoTmp3).toFixed(2);

                                    searchObj.save_fo = BigNumber(searchObj.rob_fo).minus(searchObj.used_fo).toFixed(2);
                                    searchObj.save_do = BigNumber(searchObj.rob_do).minus(searchObj.used_do).toFixed(2);

                                }

                                searchObj.total_count = searchObj.currentData.length;
                                searchObjTmp = JSON.parse(JSON.stringify(searchObj.currentData));
                                tmp = $('[name=voy_list]').val();
                            }
                        })
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
                    addItem(e, id = 0) {
                        this.currentItem.id = id;
                        if(id == 0) {
                            this.currentItem.Voy_Date = this.getToday('-');
                        } else {

                        }

                        this.$forceUpdate();
                        $('#modal-wizard').modal('show');
                    },
                    dateModify(e, index) {
                        $(e.target).on("change", function() {
                            searchObj.currentItem['Voy_Date'] = $(this).val();
                        });
                    },
                    onChangeStatus: function(e, index) {
                        let voyStatus = $(e.target).val();
                        searchObj.currentItem['dynamicSub'] = getSubList(voyStatus);
                        searchObj.currentItem['Voy_Type'] = getSubList(voyStatus)[0][0];
                        searchObj.$forceUpdate();
                    },
                    submitForm: function() {
                        submitted = true;
                        if(this.validateForm() == -2) {
                            bootbox.alert('Please input ROB/FO, ROB/DO value.');
                        } else if(this.validateForm() == -1) {
                            bootbox.alert('"CGO QTY" is require input field.');
                        } else if(this.validateForm() == -3) {
                            bootbox.alert('If voy status is "CMPLT VOYAGE", "POSITION" and "ROB" are required item.');
                        } else {
                            $('#dynamic-form').submit();
                        }

                        return false;
                    },
                    validateForm() {
                        let $this = this.currentData;
                        var retVal = true;
                        let voyageValidate = 0;
                        $this.forEach(function(value, key) {
                            if(value['Cargo_Qtty'] == '')
                                $this[key]['Cargo_Qtty'] = null;
                            if(value['Voy_Status'] == '{{ DYNAMIC_VOYAGE }}') {
                                if(__parseFloat(value['ROB_FO']) == 0 || __parseFloat(value['ROB_DO']) == 0 || __parseStr(value['Ship_Position']) == '')
                                    voyageValidate = -3;
                            }
                        });

                        if(voyageValidate != 0) return voyageValidate;

                        $this.forEach(function(value, key) {
                            if($this[key]['Voy_Status'] == DYNAMIC_CMPLT_DISCH) {
                                if(value['Cargo_Qtty'] == undefined || value['Cargo_Qtty'] == null) {
                                    retVal = -1;
                                } else if(value['Cargo_Qtty'] == 0) {
                                    if(__parseFloat(value['ROB_FO']) == 0 || __parseFloat(value['ROB_DO']) == 0) {
                                        retVal = -2;
                                    }
                                }
                            } else if($this[key]['Voy_Status'] == DYNAMIC_CMPLT_LOADING) {
                                if(value['Cargo_Qtty'] == undefined || value['Cargo_Qtty'] == null)
                                    retVal = -1;
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
                    // addRow: function(index) {
                    //     let length = this.currentData.length;
                    //     if(length != 0 && length - 1 != index)
                    //         return;

                    //     this.setDefaultData();
                    // },
                    addRow: function() {
                        // let length = this.currentData.length;
                        // if(length != 0 && length - 1 != index)
                        //     return;

                        this.setDefaultData();
                    },
                    setDefaultData() {
                        let length = searchObj.currentData.length;
                        searchObj.currentData.push([]);
                        if(length > 1) {
                            let tmp = {
                                Voy_Status: DYNAMIC_SAILING,
                                dynamicSub: getSubList(DYNAMIC_SAILING),
                                Voy_Type: DYNAMIC_SUB_SALING,
                                Voy_Hour: "08",
                                Voy_Minute: "00",
                                Voy_Date: searchObj.currentData[length - 1]['Voy_Date'],
                                GMT: searchObj.currentData[length - 1]['GMT']
                            }
                            searchObj.currentData[length] = tmp;
                        } else {
                            let tmp1 = {
                                Voy_Status: DYNAMIC_SAILING,
                                dynamicSub: getSubList(DYNAMIC_SAILING),
                                Voy_Type: DYNAMIC_SUB_SALING,
                                Voy_Hour: "08",
                                Voy_Minute: "00",
                                Voy_Date: this.getToday('-'),
                                GMT: 8
                            }

                            searchObj.currentData[length] = tmp1;
                            searchObjTmp = JSON.parse(JSON.stringify(searchObj.currentData));
                        }

                        // searchObj.$forceUpdate();
                    },
                    limitHour: function(e) {
                        let val = parseInt(e.target.value);
                        if(val > 25)
                            this.currentItem['Voy_Hour'] = 23;
                        else if(val < 0)
                            this.currentItem['Voy_Hour'] = 0;
                        else if(val < 10 && val > 0)
                            this.currentItem['Voy_Hour'] = sprintf('%02d', val);
                        else
                            this.currentItem['Voy_Hour'] = val;
                    },
                    limitMinute: function(e) {
                        let val = parseInt(e.target.value);
                        if(val > 60)
                            this.currentItem['Voy_Minute'] = 59;
                        else if(val < 0)
                            this.currentItem['Voy_Minute'] = 0;
                        else if(val < 10 && val > 0)
                            this.currentItem['Voy_Minute'] = sprintf('%02d', val);
                        else
                            this.currentItem['Voy_Minute'] = val;
                    },
                    limitGMT: function(e) {
                        let val = parseInt(e.target.value);
                        if(val > 24)
                            this.currentItem['GMT'] = 24;
                        if(val < 0)
                            this.currentItem['GMT'] = 0;
                    },
                    deleteItem: function(id) {
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
                        if(val > 24)
                            $(this).val(24);
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
            return parseFloat(diffDay.div(24).toFixed(4));
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