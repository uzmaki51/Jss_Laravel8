
<div id="main-list" v-cloak>
    <div class="row">
        <div class="col-lg-7">
            <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名: </label>
            <select class="custom-select d-inline-block" id="select-ship" style="padding: 4px; max-width: 100px;" @change="onChangeShip" v-model="shipId">
                @foreach($shipList as $ship)
                    <option value="{{ $ship['IMO_No'] }}"
                        {{ isset($shipId) && $shipId == $ship['IMO_No'] ?  "selected" : "" }}>{{ $ship['NickName'] == '' ? $ship['shipName_En'] : $ship['NickName'] }}
                    </option>
                @endforeach
            </select>
            <label class="font-bold">航次:</label>
            <select class="text-center" style="width: 60px;" id="voy_list" @change="onChangeVoy" v-model="voyId">
                @foreach($cpList as $key => $item)
                    <option value="{{ $item->Voy_No }}">{{ $item->Voy_No }}</option>
                @endforeach
            </select>

            <strong style="font-size: 16px; padding-top: 6px; margin-left: 30px;" class="f-right">
                <span id="search_info">{{ $shipName }}</span>&nbsp;&nbsp;&nbsp;<span class="font-bold">@{{ voyId }}次评估</span>
            </strong>
            
        </div>
        <div class="col-lg-5">
            <div class="btn-group f-right">
                <a class="btn btn-sm btn-default" @click="openNewPage('soa')">SOA</a>
                <a class="btn btn-sm btn-default" @click="openNewPage('dynamic')">动态分析</a>
                <button class="btn btn-warning btn-sm excel-btn" @click="fnExcelMain()"><i class="icon-table"></i><b>{{ trans('common.label.excel') }}</b></button>
            </div>
        </div>
    </div>
    
    <div class="row" style="margin-top: 4px;">
        <div class="col-lg-12 head-fix-div common-list">
            <table class="evaluation-table mt-2 table-striped" id="table-main">
                <tr>
                    <td style="width: 20%;">航次</td>
                    <td colspan="2" style="width: 30%;">@{{ cpInfo.Voy_No }}</td>
                    <td style="width: 20%;">装率（交船地点）</td>
                    <td style="width: 30%;">@{{ cpInfo.L_Rate }}</td>
                </tr>
                <tr>
                    <td>合同日期</td>
                    <td colspan="2">@{{ cpInfo.CP_Date }}</td>
                    <td>卸率（还船地点）</td>
                    <td>@{{ cpInfo.D_Rate }}</td>
                </tr>
                <tr>
                    <td>租船种类</td>
                    <td colspan="2">@{{ cpInfo.CP_kind }}</td>
                    <td>运费率（日租金）</td>
                    <td>@{{ cpInfo.Freight }}</td>
                </tr>
                <tr>
                    <td>货名</td>
                    <td colspan="2">@{{ cpInfo.Cargo_Name }}</td>
                    <td>包船（首付金）</td>
                    <td>@{{ cpInfo.batch_price }}</td>
                </tr>
                <tr>
                    <td>货量（租期）</td>
                    <td colspan="2">@{{ cpInfo.Cgo_Qtty }}</td>
                    <td>滞期费（ILOHC）</td>
                    <td>@{{ cpInfo.deten_fee }}</td>
                </tr>
                <tr>
                    <td>装港</td>
                    <td colspan="2">@{{ cpInfo.lport }}</td>
                    <td>速遣费（C/V/E）</td>
                    <td>@{{ cpInfo.dispatch_fee }}</td>
                </tr>
                <tr>
                    <td>卸港</td>
                    <td colspan="2">@{{ cpInfo.dport }}</td>
                    <td>佣金</td>
                    <td>@{{ cpInfo.com_fee }}</td>
                </tr>
                <tr>
                    <td>受载期</td>
                    <td>@{{ cpInfo.LayCan_Date1 }}</td>
                    <td style="background: white!important;">@{{ cpInfo.LayCan_Date2 }}</td>
                    <td style="background: #d9f8fb!important">租家</td>
                    <td style="background: white!important;">@{{ cpInfo.charterer }}</td>
                </tr>
            </table>
            <table class="mt-2 table-striped main-info-table" id="table-main-2">
                <tr class="dynamic-footer">
                    <td class="center" style="width: 5%">No.</td>
                    <td class="center" colspan="2" style="width: 20%">项目</td>
                    <td class="center" style="width: 15%">预计</td>
                    <td class="center" style="width: 15%">实际</td>
                    <td class="center" style="width: 15%">方差</td>
                    <td class="center" style="width: 30%"></td>
                </tr>

                <tbody>
                    <tr class="even">
                        <td class="center">1</td>
                        <td colspan="2">期间</td>
                        <td colspan="3" class="center">@{{ realInfo.start_date == 'undefined' ? '' : realInfo.start_date }} ~ @{{ realInfo.end_date == 'undefined' ? '' : realInfo.end_date }}</td>
                        <td rowspan="12">
                            <div id="economic_graph" style="height: 200px;"></div>
                        </td>
                    </tr>
                    <tr class="odd">
                        <td class="center">2</td>
                        <td colspan="2">速度 (Kn)</td>
                        <td class="text-right">@{{ number_format(cpInfo.speed) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.avg_speed) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.avg_speed - cpInfo.speed) }}</td>
                    </tr>

                    <tr class="even">
                        <td class="center">3</td>
                        <td colspan="2">里程 (NM)</td>
                        <td class="text-right">@{{ number_format(cpInfo.distance) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.total_distance) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.total_distance - cpInfo.distance) }}</td>
                    </tr>
                    <tr class="odd">
                        <td class="center" rowspan="5">4</td>
                        <td colspan="2">航次用时</td>
                        <td class="text-right">@{{ number_format(cpInfo.sail_time) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.total_sail_time) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.total_sail_time - cpInfo.sail_time) }}</td>
                    </tr>

                    <tr class="even">
                        <td rowspan="4" class="center">其中</td>
                        <td class="text-right">装货天数</td>
                        <td class="text-right">@{{ number_format(cpInfo.up_ship_day) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.load_time) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.load_time - cpInfo.up_ship_day) }}</td>
                    </tr>

                    <tr class="odd">
                        <td class="text-right">卸货天数</td>
                        <td class="text-right">@{{ number_format(cpInfo.down_ship_day) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.disch_time) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.disch_time - cpInfo.down_ship_day) }}</td>
                    </tr>
                    <tr class="even">
                        <td class="text-right">等待天数</td>
                        <td class="text-right">@{{ number_format(cpInfo.wait_day) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.wait_time) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.wait_time - cpInfo.wait_day) }}</td>
                    </tr>
                    <tr class="odd">
                        <td class="text-right">航行天数</td>
                        <td class="text-right">@{{ number_format(cpInfo.sail_term) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.sail_time) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.sail_time - cpInfo.sail_term) }}</td>
                    </tr>

                    <tr class="odd">
                        <td class="text-center" rowspan="2">5</td>
                        <td rowspan="2">耗油</td>
                        <td>FO (MT)</td>
                        <td class="text-right">@{{ number_format(realInfo.used_fo) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.rob_fo) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.rob_fo - realInfo.used_fo) }}</td>
                    </tr>
                    <tr class="even">
                        <td>DO (MT)</td>
                        <td class="text-right">@{{ number_format(realInfo.used_do) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.rob_do) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.rob_do - realInfo.used_do) }}</td>
                    </tr>

                    <tr class="even">
                        <td class="text-center" rowspan="2">6</td>
                        <td rowspan="2">油价</td>
                        <td>FO ($/MT)</td>
                        <td class="text-right">@{{ number_format(cpInfo.fo_price) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.rob_fo_price) }}</td>
                        <td class="text-right">@{{ number_format(cpInfo.fo_price - realInfo.rob_fo_price) }}</td>
                    </tr>
                    <tr class="odd">
                        <td>DO ($/MT)</td>
                        <td class="text-right">@{{ number_format(cpInfo.do_price) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.rob_do_price) }}</td>
                        <td class="text-right">@{{ number_format(cpInfo.do_price - realInfo.rob_do_price) }}</td>
                    </tr>

                    <tr class="even">
                        <td class="center">7</td>
                        <td colspan="2">货量（租期）</td>
                        <td class="text-right">@{{ number_format(cpInfo.Cgo_Qtty) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.cgo_qty) }}</td>
                        <td class="text-right">@{{ number_format(cpInfo.Cgo_Qtty - realInfo.cgo_qty) }}</td>
                        <td  rowspan="13">
                            <div id="debit_graph" style="height: 250px;"></div>
                        </td>
                    </tr>

                    <tr class="odd">
                        <td class="center">8</td>
                        <td colspan="2" class="text-profit font-weight-bold">收入</td>
                        <td class="text-right text-profit">@{{ number_format(cpInfo.credit) }}</td>
                        <td class="text-right text-profit">@{{ number_format(realInfo.credit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.credit - cpInfo.credit) }}</td>
                    </tr>


                    <tr class="even">
                        <td class="center" rowspan="5">9</td>
                        <td colspan="2" class="font-weight-bold">支出</td>
                        <td class="text-right">@{{ number_format(cpInfo.debit) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.debit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.debit - cpInfo.debit) }}</td>
                    </tr>

                    <tr class="odd">
                        <td rowspan="4" class="center">其中</td>
                        <td class="text-right">装卸港费</td>
                        <td class="text-right text-warning">@{{ number_format(__parseFloat(cpInfo.up_port_price) + __parseFloat(cpInfo.down_port_price)) }}</td>
                        <td class="text-right">@{{ number_format(__parseFloat(realInfo.sail_credit)) }}</td>
                        <td class="text-right">@{{ number_format(__parseFloat(realInfo.sail_credit) - __parseFloat(cpInfo.up_port_price) - __parseFloat(cpInfo.down_port_price)) }}</td>
                    </tr>

                    <tr class="even">
                        <td class="text-right">耗油成本</td>
                        <td class="text-right">@{{ number_format(cpInfo.fuel_consumpt) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.fuel_consumpt) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.fuel_consumpt - cpInfo.fuel_consumpt) }}</td>
                    </tr>
                    <tr class="odd">
                        <td class="text-right">其他(运营)</td>
                        <td class="text-right">@{{ number_format(cpInfo.cost_else) }}</td>
                        <td class="text-right text-warning">@{{ number_format(cpInfo.cost_else) }}</td>
                        <td class="text-right"></td>
                    </tr>
                    <tr class="even">
                        <td class="text-right">管理成本</td>
                        <td class="text-right">@{{ number_format(cpInfo.manage_cost_day) }}</td>
                        <td class="text-right text-warning">@{{ number_format(realInfo.manage_cost_day) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.manage_cost_day - cpInfo.manage_cost_day) }}</td>
                    </tr>


                    <tr class="odd">
                        <td class="center">10</td>
                        <td colspan="2">毛利润</td>
                        <td class="text-right">@{{ number_format(cpInfo.gross_profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.gross_profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.gross_profit - cpInfo.gross_profit) }}</td>
                    </tr>

                    <tr class="even">
                        <td class="center">11</td>
                        <td colspan="2">日毛利润</td>
                        <td class="text-right">@{{ number_format(cpInfo.day_gross_profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.day_gross_profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.day_gross_profit - cpInfo.day_gross_profit) }}</td>
                    </tr>
                    <tr class="odd">
                        <td class="center">12</td>
                        <td colspan="2">日均成本</td>
                        <td class="text-right">@{{ number_format(realInfo.cost_day) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.cost_day) }}</td>
                        <td class="text-right"></td>
                    </tr>
                    <tr class="even">
                        <td class="center">13</td>
                        <td colspan="2">净利润</td>
                        <td class="text-right">@{{ number_format(cpInfo.profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.profit - cpInfo.profit) }}</td>
                    </tr>
                    <tr class="odd">
                        <td class="center">14</td>
                        <td colspan="2">日净利润</td>
                        <td class="text-right">@{{ number_format(cpInfo.day_profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.day_profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.day_profit - cpInfo.day_profit) }}</td>
                    </tr>
                    <tr class="even">
                        <td class="center">15</td>
                        <td colspan="2">预计利润(1年)</td>
                        <td class="text-right">@{{ number_format(cpInfo.year_profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.year_profit) }}</td>
                        <td class="text-right">@{{ number_format(realInfo.year_profit - cpInfo.year_profit) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

    <script src="{{ cAsset('/assets/js/highcharts.js') }}"></script>

	<?php
	echo '<script>';
    echo 'var PlaceType = ' . json_encode(g_enum('PlaceType')) . ';';
    echo 'var VarietyType = ' . json_encode(g_enum('VarietyType')) . ';';
    echo 'var UnitData = ' . json_encode(g_enum('UnitData')) . ';';
	echo '</script>';
	?>
    <script>
        var equipObj = null;
        var $_this = null;
        var shipCertTypeList = [];
        var equipObjTmp = [];
        var certIdList = [];
        var shipId = '{!! $shipId !!}';
        var voyId = '{!! $voyId !!}';
        var activeVoy = $('#voy_list').val();
        var isChangeStatus = false;

        var economicGraph = null;
        var economicGraph1 = null;
        var economicGraph2 = null;
        var economicGraph3 = null;
        var economicGraph4 = null;
        var initLoad = true;
        var activeId = 0;

        function initRecord() {
            $('.year-title').text(activeVoy);
            getInitRecInfo(shipId, voyId);
        }

        function getInitRecInfo(shipId, voyId) {
            $.ajax({
                url: BASE_URL + 'ajax/shipManage/evaluation/list',
                type: 'post',
                data: {
                    shipId: shipId,
                    voyId: voyId,
                },
                success: function(data, status, xhr) {
                    let cpInfo = data['cpInfo'];
                    let realInfo = data['realInfo'];

                    equipObj = new Vue({
                        el: '#main-list',
                        data: {
                            shipId:         shipId,
                            voyId:          voyId,

                            cpInfo:         [],
                            realInfo:       [],

                            economicGrahp:  [],
                            debitGrahp:     [],
                        },
                        methods: {
                            dateModify(e, index, type) {
                                $(e.target).on("change", function() {
                                    equipObj.list[index][type] = $(this).val();
                                });
                            },
                            onChangeShip: function(e) {
                                location.href = '/shipManage/voy/evaluation?shipId=' + $_this.shipId + '&type=main';
                            },
                            onChangeVoy: function(e) {
                                location.href = '/shipManage/voy/evaluation?shipId=' + $_this.shipId + '&voyId=' + this.voyId + '&type=main';
                            },
                            number_format: function(value, decimal = 2) {
                                return isNaN(value) || value == 0 || value == null || value == undefined ? '' : number_format(value, decimal);
                            },
                            openNewPage: function(type) {
                                if(type == 'soa') {
                                    window.open(BASE_URL + 'business/contract?shipId=' + this.shipId, '_blank');
                                } else {
                                    window.open(BASE_URL + 'shipManage/dynamicList?shipId=' + this.shipId + '&type=analyze', '_blank');
                                }
                            },
                            fnExcelMain: function() {
                                var tab_text = "";
                                tab_text +="<table border='1px' style='text-align:center;vertical-align:middle;'>";
                                real_tab = document.getElementById('table-main');
                                var tab = real_tab.cloneNode(true);
                                tab_text=tab_text+"<tr><td colspan='5' style='font-size:24px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>" + $('#search_info').html() + '_'  + $('#voy_list').val() + "次评估" + "</td></tr>";
                                
                                var j;
                                for(j=0;j<tab.rows.length-1;j++)
                                {
                                    tab.rows[j].childNodes[0].style.backgroundColor = '#d9f8fb';
                                    tab.rows[j].childNodes[4].style.backgroundColor = '#d9f8fb';
                                    tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
                                }
                                tab.rows[j].childNodes[0].style.backgroundColor = '#d9f8fb';
                                tab.rows[j].childNodes[6].style.backgroundColor = '#d9f8fb';
                                tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
                                tab_text=tab_text+"</table>";

                                tab_text +="<table border='1px' style='text-align:center;vertical-align:middle;'>";
                                tab_text +="<tr colspan='6'><td style='height:20px;'></td></tr>"
                                real_tab = document.getElementById('table-main-2');
                                tab = real_tab.cloneNode(true);
                                for(j = 0; j < tab.rows.length ; j++)
                                {
                                    if (j==0) {
                                        for (var i=0; i<tab.rows[j].childElementCount*2;i+=2) {
                                            tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                                        }
                                    }
                                    else
                                    {
                                        for (var i=0; i<tab.rows[j].childElementCount*2;i+=2) {
                                            var node = tab.rows[j].childNodes[i].childNodes[0];
                                            if ( node != undefined)
                                            {
                                                var type = node.nodeType;
                                                var value;
                                                if (type == 3) continue;
                                                if (node.tagName=='DIV') {
                                                    tab.rows[j].childNodes[i].innerHTML = "";
                                                }
                                                else if(node.tagName=='INPUT'){
                                                    console.log(j,i,type,node);
                                                    value = node.value;
                                                    tab.rows[j].childNodes[i].innerHTML = value;
                                                    
                                                }
                                            }
                                        }
                                    }
                                    if (tab.rows[j].lastChild.className.indexOf('no-border') >= 0) {
                                        tab.rows[j].lastChild.remove();
                                    }
                                    tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
                                }
                                tab_text=tab_text+"</table>";
                                
                                tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");
                                tab_text= tab_text.replace(/<img[^>]*>/gi,"");
                                tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, "");

                                var filename = $('#search_info').html() + '_'  + $('#voy_list').val() + "次评估";
                                exportExcel(tab_text, filename, filename);
                                
                                return 0;
                            }
                        },
                        mounted: function() {
                            economicGraph = Highcharts.chart('economic_graph', {
                                chart: {
                                    plotBackgroundColor: null,
                                    plotBorderWidth: null,
                                    plotShadow: false,
                                    type: 'pie'
                                },
                                title: {
                                    text: '天数占率'
                                },
                                tooltip: {
                                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                },
                                exporting: { enabled: false },
                                credits: {
                                    enabled: false
                                },
                                accessibility: {
                                    point: {
                                    valueSuffix: '%'
                                    }
                                },
                                plotOptions: {
                                    pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: true,
                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        connectorColor: 'silver'
                                    }
                                    }
                                },
                                series: [{
                                    name: '天数占率',
                                    data: [
                                    { name: '等待天数', y: realInfo.wait_time },
                                    { name: '航次天数', y: realInfo.sail_time },
                                    { name: '装货天数', y: realInfo.load_time },
                                    { name: '卸货天数', y: realInfo.disch_time },
                                    ]
                                }]
                            });                    

                            Highcharts.chart('debit_graph', {
                                chart: {
                                    plotBackgroundColor: null,
                                    plotBorderWidth: null,
                                    plotShadow: false,
                                    type: 'pie'
                                },
                                title: {
                                    text: '支出占率'
                                },
                                tooltip: {
                                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                },
                                accessibility: {
                                    point: {
                                        valueSuffix: '%'
                                    }
                                },
                                credits: {
                                    enabled: false
                                },
                                exporting: { enabled: false },
                                plotOptions: {
                                    pie: {
                                        allowPointSelect: true,
                                        cursor: 'pointer',
                                        dataLabels: {
                                            enabled: true,
                                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                            connectorColor: 'silver'
                                        }
                                    }
                                },
                                series: [{
                                    name: '支出占率',
                                    data: [
                                        { name: '装卸港费', y: __parseFloat(realInfo.sail_credit) },
                                        { name: '耗油成本', y: __parseFloat(realInfo.fuel_consumpt) },
                                        { name: '其他(运营)', y: __parseFloat(cpInfo.cost_else) },
                                        { name: '管理成本', y: __parseFloat(realInfo.manage_cost_day) },
                                    ]
                                }]
                            });
                        },
                        updated() {
                            $('.date-picker').datepicker({
                                autoclose: true,
                            }).next().on(ace.click_event, function () {
                                $(this).prev().focus();
                            });
                        }
                    });

                    $_this = equipObj;
                    $_this.voyId = voyId;

                    $_this.cpInfo = Object.assign([], [], cpInfo);
                    $_this.realInfo = Object.assign([], [], realInfo);

                    let tmp1 = BigNumber($_this.realInfo.used_fo).multipliedBy($_this.cpInfo.fo_price).toFixed(2);
                    let tmp2 = BigNumber($_this.realInfo.used_do).multipliedBy($_this.cpInfo.do_price).toFixed(2);
                    $_this.cpInfo['fuel_consumpt'] = BigNumber(tmp1).plus(tmp2).toFixed(2);

                    tmp1 = BigNumber($_this.realInfo.rob_fo).multipliedBy($_this.realInfo.rob_fo_price).toFixed(2);
                    tmp2 = BigNumber($_this.realInfo.rob_do).multipliedBy($_this.realInfo.rob_do_price).toFixed(2);
                    $_this.realInfo['fuel_consumpt'] = BigNumber(tmp1).plus(tmp2).toFixed(2);

                    let debitTmp1 = BigNumber($_this.cpInfo['up_port_price']).plus($_this.cpInfo['down_port_price']);
                    let debitTmp2 = BigNumber($_this.cpInfo['fuel_consumpt']).plus($_this.cpInfo['cost_else']);
                    $_this.cpInfo['manage_cost_day'] = BigNumber($_this.realInfo['cost_day']).multipliedBy($_this.cpInfo['sail_time']).toFixed(2);
                    $_this.cpInfo['debit'] = debitTmp1.plus(debitTmp2).plus($_this.cpInfo['manage_cost_day']).toFixed(2);

                    debitTmp1 = $_this.realInfo['sail_credit'];
                    debitTmp2 = BigNumber($_this.realInfo['fuel_consumpt']).plus($_this.cpInfo['cost_else']);
                    $_this.realInfo['manage_cost_day'] = BigNumber($_this.realInfo['cost_day']).multipliedBy($_this.realInfo['total_sail_time']).toFixed(2);
                    $_this.realInfo['debit'] = BigNumber(debitTmp1).plus(debitTmp2).plus($_this.realInfo['manage_cost_day']).toFixed(2);

                    $_this.cpInfo['profit'] = BigNumber($_this.cpInfo['credit']).minus($_this.cpInfo['debit']).toFixed(2);
                    $_this.realInfo['profit'] = BigNumber($_this.realInfo['credit']).minus($_this.realInfo['debit']).toFixed(2);

                    $_this.cpInfo['day_profit'] = BigNumber($_this.cpInfo['profit']).div($_this.cpInfo['sail_time']).toFixed(2);
                    $_this.realInfo['day_profit'] = BigNumber($_this.realInfo['profit']).div($_this.realInfo['total_sail_time']).toFixed(2);

                    $_this.cpInfo['year_profit'] = BigNumber($_this.cpInfo['day_profit']).multipliedBy(360).toFixed(2);
                    $_this.realInfo['year_profit'] = BigNumber($_this.realInfo['day_profit']).multipliedBy(360).toFixed(2);

                    $_this.cpInfo['gross_profit'] = BigNumber($_this.cpInfo['profit']).plus($_this.cpInfo['manage_cost_day']).toFixed(2);
                    $_this.realInfo['gross_profit'] = BigNumber($_this.realInfo['profit']).plus($_this.realInfo['manage_cost_day']).toFixed(2);

                    $_this.cpInfo['day_gross_profit'] = BigNumber($_this.cpInfo['gross_profit']).div($_this.cpInfo['sail_time']).toFixed(2);
                    $_this.realInfo['day_gross_profit'] = BigNumber($_this.realInfo['gross_profit']).plus($_this.realInfo['total_sail_time']).toFixed(2);

                    $_this.test = 25;
                    economicGraph1 = BigNumber($_this.realInfo.wait_time).div($_this.realInfo.total_sail_time).multipliedBy(100).toFixed(2);
                    economicGraph2 = BigNumber($_this.realInfo.sail_time).div($_this.realInfo.total_sail_time).multipliedBy(100).toFixed(2);
                    economicGraph3 = BigNumber($_this.realInfo.load_time).div($_this.realInfo.total_sail_time).multipliedBy(100).toFixed(2);
                    economicGraph4 = BigNumber($_this.realInfo.load_time).div($_this.realInfo.total_sail_time).multipliedBy(100).toFixed(2);
                    economicGraph.series[0].data[0].options.y = economicGraph1;
                }
            });
        }

        $('#select-ship').on('change', function() {
            let val = $(this).val();
            $_this.shipId = val;
            location.href = "/shipManage/voy/evaluation?shipId=" + $(this).val();
        });

        $('#voy_list').on('change', function() {
            $('.year-title').text($(this).val());
            $_this.voyId = $(this).val();
            location.href = "/shipManage/voy/evaluation?shipId=" + $_this.shipId + '&voyId=' + $(this).val();
        });

    </script>