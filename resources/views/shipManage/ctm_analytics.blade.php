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
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4>
                        <b>CTM分析</b>
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs ship-register">
                        <li class="active">
                            <a data-toggle="tab" href="#total_analytics_div">
                                CTM收支</span>
                            </a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#debit_analytics_div">
                                支出分析</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content pt-2">
                        <div id="total_analytics_div" class="tab-pane active">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名: </label>
                                    <select class="custom-select d-inline-block" id="select-ship" style="padding: 4px; max-width: 100px;" @change="goToUrl">
                                        @foreach($shipList as $ship)
                                            <option value="{{ $ship['IMO_No'] }}"
                                                    {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select class="text-center ml-1" style="width: 60px;" id="year_list" @change="changeYear">
                                        @foreach($yearList as $key => $item)
                                            <option value="{{ $item }}" {{ $activeYear == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="btn-group f-right">
                                        <button class="btn btn-warning btn-sm excel-btn"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="head-fix-div" style="margin-top: 4px;">
                                    <table class="" v-cloak>
                                        <thead class="">
                                            <tr class="ctm-analytics">
                                                <th colspan="4">
                                                    {{ $shipName['shipName_En'] }}&nbsp;&nbsp;&nbsp;@{{ activeYear }}年 CTM(￥)
                                                </th>
                                                <th colspan="3" style="border-left: 2px solid #000;">
                                                    CTM($)
                                                </th>
                                                <th style="border-left: 3px solid #000;">
                                                    支出(￥ + $)
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-center style-header center">月份</th>
                                                <th class="text-center style-header center">收入</th>
                                                <th class="text-center style-header">支出(￥)</th>
                                                <th class="text-center style-header">余额(￥)</th>
                                                <th class="text-center style-header" style="border-left: 2px solid #000!important">收入($)</th>
                                                <th class="text-center style-header">支出($)</th>
                                                <th class="text-center style-header">余额($)</th>
                                                <th class="text-center style-header" style="border-left: 3px solid #000!important;">支出合计</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="prev-voy">
                                            <td style="height: 17px;"></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right font-weight-bold" :style="debitClass(before.cny_balance)">
                                                @{{ number_format(before.cny_balance) }}
                                            </td>
                                            <td style="border-left: 2px solid #000!important"></td>
                                            <td></td>
                                            <td class="text-right font-weight-bold" :style="debitClass(before.usd_balance)">
                                                @{{ number_format(before.usd_balance, 2, '$') }}
                                            </td>
                                            <td style="border-left: 3px solid #000!important"></td>
                                        </tr>
                                        <tr v-for="(item, array_index, key) in list">
                                            <td class="">
                                                @{{ array_index }}
                                            </td>
                                            <td class="right" :class="creditClass(item.CNY.credit)">
                                                @{{ number_format(item.CNY.credit) }}
                                            </td>
                                            <td class="right" :style="debitClass(item.CNY.debit)">
                                                @{{ number_format(item.CNY.debit) }}
                                            </td>
                                            <td class="right" :style="debitClass(item.CNY.credit - item.CNY.debit)">
                                                @{{ calcBalance(item.CNY.credit, item.CNY.debit) }}
                                            </td>
                                            <td class="right" style="border-left: 2px solid #000!important" :class="creditClass(item.USD.credit)">
                                                @{{ number_format(item.USD.credit, 2, '$') }}
                                            </td>
                                            <td class="right" :style="debitClass(item.USD.debit)">
                                                @{{ number_format(item.USD.debit, 2, '$') }}
                                            </td>
                                            <td class="right" :style="debitClass(item.USD.credit - item.USD.credit)">
                                                @{{ calcBalance(item.USD.credit, item.USD.debit, '$') }}
                                            </td>
                                            <td class="center" style="border-left: 3px solid #000!important" :style="debitClass(item.USD.debit + item.CNY.usd_debit)">
                                                @{{ calcUsd(item.USD.debit, item.CNY.usd_debit) }}
                                            </td>
                                        </tr>
                                        <tr class="fixed-footer">
                                            <td class="style-header center" style="width: 4%;">合计</td>
                                            <td class="style-header right" style="width: 14%;" :class="creditClass(total.cny.credit)">@{{ number_format(total.cny.credit, 2) }}</td>
                                            <td class="style-header right" style="width: 14%;" :style="debitClass(total.cny.debit)">@{{ number_format(total.cny.debit, 2) }}</td>
                                            <td class="style-header right" style="width: 14%;" :style="debitClass(total.cny.credit - total.cny.debit)">@{{ number_format(total.cny.balance, 2) }}</td>
                                            <td class="style-header right" style="width: 14%; border-left: 2px solid #000!important" :class="creditClass(total.usd.credit)">@{{ number_format(total.usd.credit, 2, '$') }}</td>
                                            <td class="style-header right" style="width: 14%;" :style="debitClass(total.usd.debit)">@{{ number_format(total.usd.debit, 2, '$') }}</td>
                                            <td class="style-header right" style="width: 14%;" :style="debitClass(total.usd.credit - total.usd.debit)">@{{ number_format(total.usd.balance, 2, '$') }}</td>
                                            <td class="style-header center" style="width: 14%; border-left: 3px solid #000!important;" :style="debitClass(total.usd.debit + total.cny.usd_amount)">@{{ calcUsd(total.usd.debit, total.cny.usd_amount) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div id="debit_analytics_div" class="tab-pane">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名: </label>
                                    <select class="custom-select d-inline-block" id="select-ship" style="padding: 4px; max-width: 100px;" @change="goToUrl">
                                        @foreach($shipList as $ship)
                                            <option value="{{ $ship['IMO_No'] }}"
                                                    {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select class="text-center ml-1" style="width: 60px;" @change="changeYear">
                                        @foreach($yearList as $key => $item)
                                            <option value="{{ $item }}" {{ $activeYear == $item ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="btn-group f-right">
                                        <button class="btn btn-warning btn-sm excel-btn"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="head-fix-div" style="margin-top: 4px;">
                                        <table class="" v-cloak>
                                            <thead class="">
                                                <tr class="ctm-analytics">
                                                    <th colspan="13">
                                                        {{ $shipName['shipName_En'] }}&nbsp;&nbsp;&nbsp;@{{ activeYear }}年 支出分析
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="style-header center" style="width: 4%;">月份</th>
                                                    <th class="style-header center" style="border-right: 2px solid #000!important;">支出合计($)</th>
                                                    <th  class="style-header center" v-for="(item, index) in profitType">@{{ item }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index, key) in list">
                                                    <td class="center">@{{ index }}</td>
                                                    <td class="right" style="border-right: 2px solid #000!important;">@{{ number_format(item.debitTotal) }}</td>
                                                    <td class="right" v-for="(subItem, subIndex) in item" v-show="subIndex <= 12">@{{ number_format(subItem) }}</td>
                                                </tr>
                                                <tr class="fixed-footer">
                                                    <td class="style-header center" style="width: 40px;">合计</td>
                                                    <td class="style-header right" v-for="(item, index) in total" :style="index == 1 ? 'border-right: 2px solid #000!important;' : ''">@{{ number_format(item) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker/dist/locale/translations/zh.js"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>
    <script src="{{ asset('/assets/js/dycombo.js') }}"></script>
    <script src="{{ cAsset('assets/js/bignumber.js') }}"></script>

	<?php
	echo '<script>';
    echo 'var ProfitDebitData = ' . json_encode(g_enum('ProfitDebitData')) . ';';
	echo '</script>';
	?>
    <script>
        var totalAnalticsObj = null;
        var debitAnalyticsObj = null;
        var shipId = '{!! $shipId !!}';
        var activeYear = '{!! $activeYear !!}';
        var activeType = '{!! $type !!}';

        var _totalThis = null;
        var _debitThis = null;

        $(function() {
            __init();
        });

        function __init() {
            createVueObj();
        }

        function createVueObj() {
            totalAnalyticsObj = new Vue({
                el: '#total_analytics_div',
                data: {
                    list: [],

                    shipId: shipId,
                    activeYear: activeYear,
                    activeType: activeType,

                    total: {
                        cny: {
                            credit: 0,
                            debit: 0,
                            balance: 0,
                            usd_amount: 0,
                        },
                        usd: {
                            credit: 0,
                            debit: 0,
                            balance: 0,
                        },
                        usd_amount: 0
                    },
                    before: {
                        cny_balance: 0,
                        usd_balance: 0,
                    }
                },
                methods: {
                    goToUrl: function(e) {
                        let val = e.target.value;
                        location.href = '/shipManage/ctm/analytics?shipId=' + val + '&type=total';;
                    },
                    number_format: function(value, decimal = 2, prefix = '￥') {
                        return isNaN(value) || value == null || value == 0 ? '' : prefix + ' ' + number_format(value, decimal);
                    },
                    creditClass: function(value) {
                        return value < 0 ? 'text-danger' : 'text-profit';
                    },
                    debitClass: function(value) {
                        return value < 0 ? 'color: red!important;' : '';
                    },
                    calcBalance: function(credit, debit, prefix = '￥') {
                        return this.number_format(BigNumber(credit).minus(debit).toFixed(2), 2, prefix);
                    },
                    calcUsd: function(credit, debit) {
                        let usd = __parseFloat(credit);
                        let cny = __parseFloat(debit);
                        return this.number_format(BigNumber(usd).plus(cny).toFixed(2), 2, '$');
                    },
                    changeYear: function(e) {
                        let val = e.target.value;
                        this.activeYear = val;
                        getTotalAnalyticsObj();
                    }
                }
            });

            _totalThis = totalAnalyticsObj;
            _totalThis.shipId = shipId;
            _totalThis.activeYear = activeYear;
            _totalThis.activeType = activeType;

            
            getTotalAnalyticsObj();

            debitAnalyticsObj = new Vue({
                el: '#debit_analytics_div',
                data: {
                    list: [],
                    profitType: ProfitDebitData,

                    shipId: shipId,
                    activeYear: activeYear,
                    activeType: activeType,

                    total: [],
                },
                methods: {
                    number_format: function(value, decimal = 2, prefix = '$') {
                        return isNaN(value) || value == null || value == 0 ? '' : prefix + ' ' + number_format(value, decimal);
                    },
                    goToUrl: function(e) {
                        let val = e.target.value;
                        location.href = '/shipManage/ctm/analytics?shipId=' + val + '&type=debit';;
                    },
                    changeYear: function(e) {
                        let val = e.target.value;
                        this.activeYear = val;
                        getDebitAnalyticsObj();
                    }
                },
            });

            _debitThis = debitAnalyticsObj;
            _totalThis.shipId = shipId;
            _totalThis.activeYear = activeYear;
            _totalThis.activeType = activeType;

            getDebitAnalyticsObj();

        }


        function getTotalAnalyticsObj() {
            $.ajax({
                url: BASE_URL + 'ajax/shipmanage/ctm/total',
                type: 'post',
                data: {
                    shipId: _totalThis.shipId,
                    year: _totalThis.activeYear,
                },
                success: function(data) {
                    let result = data['current'];
                    let before = data['before'];
                    _totalThis.list = result;
                    if(before.cny != null && before.cny != undefined)
                        _totalThis.before.cny_balance = __parseFloat(before.cny.balance);
                    else
                        _totalThis.before.cny_balance = 0;

                    if(before.usd != null && before.usd != undefined)
                        _totalThis.before.usd_balance = __parseFloat(before.usd.balance);
                    else
                        _totalThis.before.usd_balance = 0;
                    
                    _totalThis.total.cny.credit = 0;
                    _totalThis.total.usd.credit = 0;
                    _totalThis.total.cny.debit = 0;
                    _totalThis.total.usd.debit = 0;
                    _totalThis.total.cny.credit = 0;
                    _totalThis.total.cny.balance = 0;
                    _totalThis.total.usd.balance = 0;
                    _totalThis.total.cny.credit = 0;
                    _totalThis.total.cny.usd_amount = 0;

                    for(var i = 1; i <= 12; i ++) {
                        let item = _totalThis.list[i];
                        if(item['CNY'].credit != null) {
                            _totalThis.total.cny.credit += __parseFloat(item['CNY'].credit);
                        }
                        if(item['USD'].credit != null) {
                            _totalThis.total.usd.credit += __parseFloat(item['USD'].credit);
                        }
                        if(item['CNY'].debit != null) {
                            _totalThis.total.cny.debit += __parseFloat(item['CNY'].debit);
                        }
                        if(item['USD'].debit != null) {
                            _totalThis.total.usd.debit += __parseFloat(item['USD'].debit);
                        }
                        if(item['CNY'].usd_debit != null) {
                            _totalThis.total.cny.usd_amount += __parseFloat(item['CNY'].usd_debit);
                        }
                    }

                    _totalThis.total.cny.balance = BigNumber(_totalThis.total.cny.credit).minus(_totalThis.total.cny.debit).plus(_totalThis.before.cny_balance).toFixed(2);
                    _totalThis.total.usd.balance = BigNumber(_totalThis.total.usd.credit).minus(_totalThis.total.usd.debit).plus(_totalThis.before.usd_balance).toFixed(2);
                    _totalThis.$forceUpdate();
                }
            })
        }

        function getDebitAnalyticsObj() {
            $.ajax({
                url: BASE_URL + 'ajax/shipmanage/ctm/debit',
                type: 'post',
                data: {
                    shipId: _debitThis.shipId,
                    year: _debitThis.activeYear,
                },
                success: function(data) {
                    let result = data;
                    _debitThis.list = result['list'];
                    _debitThis.total = result['total'];
                }
            })
        }
    </script>
@endsection