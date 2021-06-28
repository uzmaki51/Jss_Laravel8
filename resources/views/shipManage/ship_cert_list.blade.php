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
                <div class="col-sm-3">
                    <h4><b>船舶证书</b></h4>
                </div>
            </div>
            <div class="row col-md-12" id="cert_list" v-cloak>
                <div class="col-md-6">
                    <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名:</label>
                    <select class="custom-select d-inline-block" style="padding: 4px;max-width: 100px;" @change="changeShip">
                        @foreach($shipList as $ship)
                            <option value="{{ $ship['IMO_No'] }}"
                                    {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                            </option>
                        @endforeach
                    </select>
                    @if(isset($shipName['shipName_En']))
                        <strong class="f-right" style="font-size: 16px; padding-top: 6px;">"<span id="ship_name">{{ $shipName['shipName_En'] }}</span>" CERTIFICATES</strong>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="btn-group f-right">
                        <button class="btn btn-report-search btn-sm search-btn d-none" @click="doSearch()"><i class="icon-search"></i>搜索</button>
                        <a class="btn btn-sm btn-danger refresh-btn-over d-none" type="button" @click="refresh">
                            <img src="{{ cAsset('assets/images/refresh.png') }}" class="report-label-img">恢复
                        </a>
                        <button class="btn btn-warning btn-sm excel-btn" @click="onExport"><i class="icon-table"></i>{{ trans('common.label.excel') }}</button>
                    </div>
                    <div class="f-right" style="margin-right: 12px; padding-top: 2px;">
                        <label class="font-bold">提前:</label>
                        <select class="text-center" style="width: 60px;" name="expire_date" v-model="expire_date" @change="onExpireChange">
                            <option value="0">All</option>
                            <option value="90">90</option>
                            <option value="120">120</option>
                            <option value="180">180</option>
                        </select>
                        <input type="hidden" class="text-center" style="width: 60px;" name="ship_id" v-model="ship_id">
                        <label>天</label>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: 4px;">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" value="{{ $shipId }}" name="ship_id">
                    <div class="row">
                        <div class="">
                            <table class="table-bordered rank-table">
                                <thead>
                                    <th class="text-center style-header" style="width:60px;word-break: break-all;">{!! trans('shipManage.shipCertList.No') !!}</th>
                                    <th class="text-center style-header" style="width:60px;word-break: break-all;">{{ trans('shipManage.shipCertList.Code') }}</th>
                                    <th class="text-center style-header" style="width:280px;word-break: break-all;">{{ trans('shipManage.shipCertList.name of certificates') }}</th>
                                    <th class="text-center style-header" style="width:120px;word-break: break-all;">{{ trans('shipManage.shipCertList.issue_date') }}</th>
                                    <th class="text-center style-header" style="width:120px;word-break: break-all;">{{ trans('shipManage.shipCertList.expire_date') }}</th>
                                    <th class="text-center style-header" style="width:120px;word-break: break-all;">{!! trans('shipManage.shipCertList.due_endorse') !!}</th>
                                    <th class="text-center style-header" style="width:80px;word-break: break-all;">{{ trans('shipManage.shipCertList.issuer') }}</th>
                                    <th class="text-center style-header" style="width:40px;word-break: break-all;"><img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15"></th>
                                    <th class="text-center style-header" style="width:200px;word-break: break-all;">{{ trans('shipManage.shipCertList.remark') }}</th>
                                </thead>
                                <tbody>
                                <tr v-for="(item, array_index) in cert_array">
                                    <td class="center no-wrap">@{{ item.order_no }}</td>
                                    <td class="center no-wrap">@{{ item.code }}</td>
                                    <td>
                                        <div class="dynamic-select-wrapper">
                                            <div class="dynamic-select" style="color:#12539b">
                                                <div class="dynamic-select__trigger">@{{ item.cert_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="center"><span>@{{ item.issue_date }}</span></td>
                                    <td class="center"><span>@{{ item.expire_date }}</span></td>
                                    <td class="center"><span>@{{ item.due_endorse }}</span></td>
                                    <td class="center"><span>@{{ issuer_type[item.issuer] }}</span></td>
                                    <td class="text-center">
                                        <label><a v-bind:href="item.attachment_link" target="_blank" v-bind:class="[item.attachment_link == '' || item.attachment_link == undefined ? 'visible-hidden' : '']"><img src="{{ cAsset('assets/images/document.png') }}" width="15" height="15" style="cursor: pointer;"></a></label>
                                    </td>
                                    <td class="text-left"><span>@{{ item.remark }}</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>

	<?php
	echo '<script>';
	echo 'var IssuerTypeData = ' . json_encode(g_enum('IssuerTypeData')) . ';';
	echo '</script>';
	?>
    <script>
        var certListObj = null;
        var shipCertTypeList = [];

        $(function () {
            // Initialize
            initialize();
        });

        function initialize() {
            // Create Vue Obj
            certListObj = new Vue({
                el: '#cert_list',
                data: {
                    cert_array: [],
                    certTypeList: [],
                    issuer_type: IssuerTypeData,
                    expire_date: 0,
                    ship_id: 0,
                },
                methods: {
                    customFormatter(date) {
                        return moment(date).format('YYYY-MM-DD');
                    },

                    doSearch() {
                        this.getShipCertInfo();
                    },
                    changeShip(e) {
                        this.ship_id = e.target.value;console.log(e.target.value)
                        this.getShipCertInfo();
                    },
                    refresh() {
                        this.expire_date = 0;
                        this.getShipCertInfo();
                    },
                    onExport() {
                        location.href='/shipManage/shipCertExcel?id=' + this.ship_id;
                    },
                    getShipCertInfo() {
                        getShipInfo(this.ship_id, this.expire_date);
                    },
                    onExpireChange(e) {
                        this.expire_date = $(e.target).val();
                        this.getShipCertInfo();
                    }
                }
            });

            certListObj.ship_id = '{!! $shipId !!}';
            getShipInfo(certListObj.ship_id, certListObj.expire_date);
        }

        function getShipInfo(ship_id, expire_date) {
            $.ajax({
                url: BASE_URL + 'ajax/shipManage/cert/list',
                type: 'post',
                data: {
                    ship_id: ship_id,
                    expire_date: expire_date
                },
                success: function(data, status, xhr) {
                    let ship_name = data['ship_name'];
                    shipCertTypeList = data['cert_type'];
                    $('#ship_name').text(ship_name);
                    certListObj.cert_array = data['ship'];
                    certListObj.certTypeList = shipCertTypeList;
                    certListObj.ship_id = data['ship_id'];
                    certListObj.cert_array.forEach(function(value, index) {
                        setCertInfo(value['cert_id'], index);
                    });
                    totalRecord = data['ship'].length;

                }
            })
        }


        function setCertInfo(certId, index = 0) {
            shipCertTypeList.forEach(function(value, key) {
                if(value['id'] == certId) {
                    certListObj.cert_array[index]['order_no'] = value['order_no'];
                    certListObj.cert_array[index]['cert_id'] = certId;
                    certListObj.cert_array[index]['code'] = value['code'];
                    certListObj.cert_array[index]['cert_name'] = value['name'];
                    certListObj.$forceUpdate();
                }
            });
        }

        $('#select-ship').on('change', function() {
            getShipInfo($(this).val());
        });

    </script>
@endsection