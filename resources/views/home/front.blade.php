@extends('layout.header')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ cAsset('assets/css/slick.css') }}"/>
    <link href="{{ cAsset('assets/css/slides.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/js/chartjs/c3.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/css/home.css') }}" rel="stylesheet"/>

    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/multiselect.css') }}" rel="stylesheet"/>
    <script src="{{ cAsset('assets/js/multiselect.min.js') }}"></script>


    <link href="{{ cAsset('assets/js/chartjs/chartist.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ cAsset('assets/js/chartjs/flot.css') }}">
    
    
    <script src="{{ cAsset('assets/js/chartjs/chartist.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/chartjs.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/d3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/c3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/flot.js') }}"></script>

    <script type="text/javascript" src="{{ cAsset('assets/js/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ cAsset('assets/css/koala.min.1.5.js') }}"></script>

    <style>
        embed:scroll-bar{display:none}

        #currency-list tr td {
            padding: 0 4px!important;
        }
        .c3 path {
            stroke-width: 3px;
        }

        #chartist-h-bars .ct-series-a line {
            stroke: #81afe4;
            /*stroke-width: 5px;
            stroke-dasharray: 10px 20px;*/
        }

        #chartist-h-bars .ct-series-b line {
            stroke: #f58787;
        }

        #chartist-h-bars .ct-series-c line {
            stroke: #b5ce71;
        }

        #chartist-h-bars-02 .ct-series-b line {
            stroke: #f58787;
        }

        #chartist-h-bars-02 .ct-series-c line {
            stroke: #b5ce71;
        }

        #chartist-h-bars-02 .ct-series-a line {
            stroke: #81afe4;
        }

        .ship-item:hover {
            background-color: #ffe3e082;
        }

        .c3-legend-item text {
            font-size:14px;
        }

        .member-item-odd {
            background-color: #f5f5f5;
        }

        .member-item-even:hover {
            background-color: #ffe3e082;
        }

        .member-item-odd:hover {
            background-color: #ffe3e082;
        }

        table{
            border-style:dotted;
        }

        table td{
            border-style:dotted;
        }

        .td-notice-yellow {
            border: 2px solid white;
            border-top: unset!important;border-bottom: unset!important;
            color:yellow;
            font-weight:bold;
            font-size: 12px;
        }

        .td-notice-white {
            border: 2px solid white;
            border-top: unset!important;border-bottom: unset!important;
            color:white;
            font-weight:bold;
            font-size: 12px;
        }

        @-webkit-keyframes blinker {
        from {opacity: 1.0;}
        to {opacity: 0.0;}
        }
        .blink{
            text-decoration: blink;
            -webkit-animation-name: blinker;
            -webkit-animation-duration: 0.6s;
            -webkit-animation-iteration-count:infinite;
            -webkit-animation-timing-function:ease-in-out;
            -webkit-animation-direction: alternate;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="row" style="padding-top: 12px;">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="card mb-4">
                            <a href="/decision/receivedReport" style="color: white; outline: unset;" target="_blank">
                            <div class="card-header decide-title" style="cursor:pointer">
                                <div class="card-title front-span">
                                    <span class="bigger-120">等待批准</span>
                                </div>
                            </div>
                            </a>
                            <div class="card-body decide-border" style="padding: 0 0px!important;max-height:121px!important;overflow-y: auto;">
                                <table id="" style="table-layout:fixed;border:0px solid black;">
                                    <tbody class="" id="list-body" style="">
                                    @if (isset($reportList) && count($reportList) > 0)
                                    <?php $index = 1;?>
                                    @foreach ($reportList as $report)
                                        @if ($report['isvisible'] != 1)
                                        <?php $nickName=""?>
                                        @foreach($shipList as $ship)
                                            @if ($ship->IMO_No == $report['shipNo'])
                                            <?php $nickName = $ship['NickName'];?>
                                            @endif
                                        @endforeach
                                        <tr @if($index%2==0) class="member-item-odd" @else class="member-item-even" @endif>
                                            <td class="center" style="height:20px!important;">{{g_enum('ReportTypeData')[$report['flowid']]}}</td>
                                            <td class="center">{{$nickName}}</td>
                                            <td class="center">{{$report['voyNo']}}</td>
                                            <td class="center">{{($report['profit_type']!=null)&&($report['profit_type']!="")?g_enum('FeeTypeData')['Debit'][$report['profit_type']]:""}}</td>
                                            <td class="center" style="background-color:#fdb971"><span class="blink">等待</span></td>
                                            <?php $index++;?>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8">{{ trans('common.message.no_data') }}</td>
                                    </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <a href="/decision/receivedReport" style="color: white; outline: unset;" target="_blank">
                            <div class="card-header no-attachment-decide-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">等待凭证</span>
                                </div>
                            </div>
                            </a>
                            <div class="card-body no-attachment-decide-border" style="padding: 0 0px!important;max-height:141px!important;overflow-y: auto;">
                                <table id="" style="table-layout:fixed;border:0px solid black;">
                                    <tbody class="" id="list-body" style="">
                                    @if (isset($noattachments) && count($noattachments) > 0)
                                    <?php $index = 1;?>
                                    @foreach ($noattachments as $report)
                                        @if ($report['isvisible'] != 1)
                                        <?php $nickName=""?>
                                        @foreach($shipList as $ship)
                                            @if ($ship->IMO_No == $report['shipNo'])
                                            <?php $nickName = $ship['NickName'];?>
                                            @endif
                                        @endforeach
                                        <tr @if($index%2==0) class="member-item-odd" @else class="member-item-even" @endif>
                                            <td class="center" style="height:20px!important;">{{g_enum('ReportTypeData')[$report['flowid']]}}</td>
                                            <td class="center">{{$nickName}}</td>
                                            <td class="center">{{$report['voyNo']}}</td>
                                            <td class="center">{{isset(g_enum('FeeTypeData')['Debit'][$report['profit_type']])?g_enum('FeeTypeData')['Debit'][$report['profit_type']]:""}}</td>
                                            <td class="center"><img src="{{ cAsset('assets/images/paper-clip.png') }}" width="15" height="15" style="margin: 0px 0px"></td>
                                            <?php $index++;?>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8">{{ trans('common.message.no_data') }}</td>
                                    </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <a href="/shipManage/shipCertManage" style="color: white; outline: unset;" target="_blank">
                            <div class="card-header expired-cert-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">证书到期</span>
                                </div>
                            </div>
                            </a>
                            <div class="card-body expired-cert-border" style="padding: 0 0px!important;max-height:121px!important;overflow-y: auto;">
                                <table id="" style="border:0px solid black;">
                                    <thead>
                                        <td class="center decide-sub-title" style="width: 35px;">船名</td>
                                        <td class="center decide-sub-title">证书</td>
                                        <td class="center decide-sub-title" style="width: 60px;">有效期</td>
                                        <td class="center decide-sub-title" style="width: 60px;">周检日期</td>
                                    </thead>
                                    <tbody class="" id="cert-body" style="">
                                        @foreach($expireCert as $key => $item)
                                            <tr>
                                                <td>{{ $item->shipName }}</td>
                                                <td class="center"><span>{{ $item->certName }}</span></td>
                                                <td class="center">{{ date('m-d', strtotime($item->expire_date)) }}</td>
                                                <td class="center">{{ date('m-d', strtotime($item->due_endorse)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <a href="/shipManage/equipment" style="color: white; outline: unset;" target="_blank">
                            <div class="card-header decide-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">必需备件</span>
                                </div>
                            </div>
                            </a>
                            <div class="card-body decide-border" style="padding: 0 0px!important;max-height:121px!important;overflow-y: auto;">
                                <table id="" style="border:0px solid black;">
                                    <thead>
                                        <td class="center decide-sub-title">船名</td>
                                        <td class="center decide-sub-title">部门</td>
                                        <td class="center decide-sub-title">缺件</td>
                                    </thead>
                                    <tbody class="" id="equipment-body" style="">
                                        @foreach($equipment as $key => $item)
                                            <tr>
                                                <td style="width: 35px;">{{ $item->shipName }}</td>
                                                <td class="center" style="width: 65px;">{{ $item->place }}</td>
                                                <td>{{ $item->remark }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-body p-0" style="box-shadow: 0px 0px 8px 4px #d2d2d2;">
                                <div class="advertise" style="height:30px;">
                                    <div style="padding-left: 16px;">
                                        <h5 style="font-weight: bold;">动态 </h5>
                                    </div>
                                    <div class="sign_list slider text-center" style="width:100%;padding-left:10px;padding-right: 16px; margin-left: auto;">
                                        @if(isset($voyList) && count($voyList) > 0)
                                            @foreach ($voyList as $info)
                                                @if ($info['isvisible'] != 1)
                                                <?php $nickName=""?>
                                                @foreach($shipList as $ship)
                                                    @if ($ship->IMO_No == $info['Ship_ID'])
                                                    <?php $nickName = $ship['NickName'];?>
                                                    @endif
                                                @endforeach
                                                <div style="height: auto; outline: unset;">
                                                    <h5>
                                                        <a href="/shipManage/dynamicList" style="color: white; outline: unset;" target="_blank">
                                                        <table style="width:100%;border:unset!important;table-layout:fixed;">
                                                            <tbody><tr>
                                                                <td class="td-notice-yellow" style="width:4%">{{$nickName}}</td>
                                                                <td class="td-notice-white" style="width:9%">{{$info['Voy_Date']}}</td>
                                                                <td class="td-notice-white" style="width:6%">{{str_pad($info['Voy_Hour'],2,"0",STR_PAD_LEFT).str_pad($info['Voy_Minute'],2,"0",STR_PAD_LEFT)}}</td>
                                                                <td class="td-notice-yellow" style="width:15%">{{g_enum('DynamicStatus')[$info['Voy_Status']][0]}}</td>
                                                                <td class="td-notice-white" style="width:15%">{{$info['Ship_Position']}}</td>
                                                                <td class="td-notice-white" style="width:8%">{{$info['Cargo_Qtty']}}</td>
                                                                <td class="td-notice-yellow" style="width:8%">{{$info['ROB_FO']}}</td>
                                                                <td class="td-notice-yellow" style="width:8%">{{$info['ROB_DO']}}</td>
                                                                <td class="td-notice-white" style="width:8%">{{$info['BUNK_FO']}}</td>
                                                                <td class="td-notice-white" style="width:8%">{{$info['BUNK_DO']}}</td>
                                                                <td class="td-notice-white" style="border-right:unset!important;">{{$info['Remark']}}</td>
                                                            </tr></tbody>
                                                        </table>
                                                        </a>
                                                    </h5>
                                                </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <span>{{ trans('home.message.no_data') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row default-style main-panel">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top:4px;">
                                        <div class="row" style="text-align:center;">
                                            <strong class="text-center" style="font-size: 20px; padding-top: 6px;"><span id="graph_first_title"></span>利润累计比较</strong>
                                            <div class="card" id="graph_first" width="500px;">
                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                        <div class="space-10"></div>
                                        <hr class="dot-hr"/>
                                        <div class="row" style="text-align:center;">
                                            <strong class="text-center" style="font-size: 20px; padding-top: 6px;"><span id="graph_second_title"></span>收支累计比较</strong>
                                            <div class="card" id="graph_second">
                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                        <div class="space-10"></div>
                                        <hr class="dot-hr"/>
                                        <div class="row" style="text-align:center;">
                                            <strong class="text-center" style="font-size: 20px; padding-top: 6px;"><span id="graph_third_title"></span>经济天数占率比较</strong>
                                            <div class="card" id="graph_third">
                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                        <div class="space-10"></div>
                                        <hr class="dot-hr"/>
                                        <div class="row" style="text-align:center;">
                                            <strong class="text-center" style="font-size: 20px; padding-top: 6px;"><span id="graph_fourth_title"></span>支出比较</strong>
                                            <div class="card" id="graph_fourth">
                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                        <div class="space-10"></div>
                                        <hr class="dot-hr"/>
                                        <div class="row" style="text-align:center;">
                                            <strong class="text-center" style="font-size: 20px; padding-top: 6px;"><span id="graph_fifth_title"></span>CTM支出比较</strong>
                                            <div class="card" id="graph_fifth">
                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                        <div class="space-10"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header common-decide-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">审核次数 ({{$settings['report_year']}})</span>
                                </div>
                            </div>
                            <div class="card-body common-decide-border" style="padding: 0 0px!important;max-height:101px!important;overflow-y: auto;">
                                <table id="" style="table-layout:fixed;border:0px solid black;">
                                    <tbody class="" id="list-body" style="">
                                    @if (isset($reportSummary) && count($reportSummary) > 0)
                                    <?php $index = 1;?>
                                    @foreach ($reportSummary as $report)
                                        @if ($report['depart_id'] != null)
                                        <tr @if($index%2==0) class="member-item-odd" @else class="member-item-even" @endif>
                                            <td class="center">{{$report['title']}}</td>
                                            <td class="center">{{$report['count']}}</td>
                                            <td class="center">{{number_format($report['percent'],2,".",",")}} %</td>
                                            <?php $index++;?>
                                        </tr>
                                        @endif
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8">{{ trans('common.message.no_data') }}</td>
                                    </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header common-decide-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">船舶日报 ({{$settings['dyn_year']}})</span>
                                </div>
                            </div>
                            <div class="card-body common-decide-border" style="padding: 0 0px!important;max-height:101px!important;overflow-y: auto;">
                                <table id="" style="table-layout:fixed;border:0px solid black;">
                                    <thead>
                                        <td class="center decide-sub-title">船名</td>
                                        <td class="center decide-sub-title">报告次</td>
                                        <td class="center decide-sub-title">占率</td>
                                    </thead>
                                    <tbody class="" id="dyn-body" style="">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header common-decide-title">
                                <div class="card-title front-span">
                                    <span class="bigger-120">有关网站</span>
                                </div>
                            </div>
                            <div class="card-body common-decide-border" style="padding: 0 0px!important;">
                                <table id="" style="table-layout:fixed;border:0px solid black;">
                                    <tbody class="" id="sites-body" style="">
                                        @if (isset($sites) && count($sites) > 0)
                                        @foreach ($sites as $site)
                                            @if ($site['link'] != null && $site['image'] != null)
                                            <tr>
                                                <td class="center"><a href="{{$site['link']}}" target="_blank"><img src="{{$site['image']}}"></a></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ cAsset('assets/js/vue.js') }}"></script>
    <script src="{{ cAsset('assets/js/bignumber.js') }}"></script>
    <?php
	echo '<script>';
    echo 'var settings = ' . $settings . ';';
    echo 'var ships = [];';
    echo 'var shipids_all = [];';
    foreach($shipList as $ship) {
        echo 'ships["' . $ship['IMO_No'] . '"]="' . $ship['NickName'] . '";';
        echo 'shipids_all.push("'.$ship['IMO_No'].'");';
    }
	echo '</script>';
	?>
    <script>
        var index = 0;
        var shipnames_graph = "";
        var color_table = ['#73b7ff','#ff655c','#50bc16','#ffc800','#9d00ff','#ff0000','#795548','#3f51b5','#00bcd4','#e91e63','#0000ff','#00ff00','#0d273a','#cddc39','#0f184e'];
        var shipids_graph = JSON.parse(settings.graph_ship);
        for (var i=0;i<shipids_graph.length;i++) {
            var name = '<span style="color:' + color_table[i] + '">' + ships[shipids_graph[i]] + '</span>';
            shipnames_graph = shipnames_graph + (i==0?"":"+") + name;
        }
        var year_graph = settings.graph_year;
        var year_dyn = settings.dyn_year;

        $('#graph_first_title').html(shipnames_graph + " " + year_graph + "年");
        $('#graph_second_title').html(shipnames_graph + " " + year_graph + "年");
        $('#graph_third_title').html(shipnames_graph + " " + year_graph + "年");
        $('#graph_fourth_title').html(shipnames_graph + " " + year_graph + "年");
        $('#graph_fifth_title').html(shipnames_graph + " " + year_graph + "年");

        initGraphTable();
        function initGraphTable() 
        {
            $.ajax({
                url: BASE_URL + 'ajax/operation/listByAll',
                type: 'post', 
                data: {'year':year_graph, 'shipId':shipids_all},
                success: function(result) {
                    // Table 1
                    var prev_sum = 0;
                    var month_sum = [];
                    var axis_x_names = [];
                    for(var i=0;i<12;i++) month_sum[i] = 0;
                    var index = 0;
                    var datasets = [];
                    for (index=0;index<shipids_graph.length;index++) {
                        var ship_name = ships[shipids_graph[index]];
                        var ship_no = shipids_graph[index];
                        datasets[index] = {};
                        datasets[index].data = result[ship_no]['sum_months'];
                        datasets[index].label = ship_name;
                        datasets[index].borderColor = color_table[index];
                        for(var i=0;i<12;i++) {
                            value = result[ship_no]['sum_months'][i];
                            month_sum[i] += value;
                        }
                    }
                    datasets[index] = {};
                    datasets[index].data = month_sum;
                    datasets[index].label = '合计';
                    datasets[index].borderColor = 'purple';//color_table[index];
                    datasets[index].borderDash = [5, 5];
                    drawFirstGraph(datasets);

                    // Table 2
                    var credit_sum = 0;
                    var debit_sum = 0;
                    var profit_sum = [];
                    for(var i=0;i<16;i++) profit_sum[i] = 0;
                    var index = 0;
                    datasets = [];
                    var datasets4 = [];
                    for (index=0;index<shipids_graph.length;index++) {
                        var ship_name = ships[shipids_graph[index]];
                        var ship_no = shipids_graph[index];
                        var value = result[ship_no]['credit_sum'];
                        datasets[index] = {};
                        datasets[index].label = ship_name;
                        datasets[index].data = [result[ship_no]['credit_sum'], result[ship_no]['debit_sum']*(-1)];
                        datasets[index].borderColor = color_table[index];
                        datasets[index].backgroundColor = addAlpha(color_table[index],0.5);

                        datasets4[index] = {};
                        datasets4[index].label = ship_name;
                        datasets4[index].data = [];
                        datasets4[index].borderColor = color_table[index];
                        datasets4[index].backgroundColor = addAlpha(color_table[index],0.8);
                        var indexes = [2,1,6,4,15,3,5,7,8,9,10,11,12];
                        for(var i=0;i<indexes.length;i++) {
                            datasets4[index].data[i] = result[ship_no]['debits'][indexes[i]];
                        }
                    }
                    value = credit_sum;
                    value = debit_sum;
                    for(var i=0;i<13;i++) {
                        var value = profit_sum[i];
                    }
                    drawSecondGraph(datasets);
                    drawFourthGraph(datasets4);
                }
            });

            $.ajax({
                url: BASE_URL + 'ajax/business/dynamic/multiSearch',
                type: 'post', 
                data: {
                    'year':year_graph,
                    'shipId':shipids_graph,
                },
                success: function(result) {
                    var index = 0;
                    var show_index = 0;
                    var datasets = [];
                    var labels = [];
                    datasets[0] = {};
                    for (index=0;index<shipids_graph.length;index++) {
                        var ship_name = ships[shipids_graph[index]];
                        var ship_no = shipids_graph[index];

                        let data = result[ship_no]['currentData'];
                        let voyData = result[ship_no]['voyData'];
                        let cpData = result[ship_no]['cpData'];

                        let list = [];
                        let realData = [];
                        let footerData = [];
                        footerData['voy_count'] = 0;
                        footerData['voy_count'] = 0;
                        // footerData['voy_start'] = 0;
                        footerData['sail_time'] = 0;
                        footerData['total_distance'] = 0;
                        footerData['total_sail_time'] = 0;
                        footerData['total_loading_time'] = 0;
                        footerData['loading_time'] = 0;
                        footerData['disch_time'] = 0;
                        footerData['total_waiting_time'] = 0;
                        footerData['total_weather_time'] = 0;
                        footerData['total_repair_time'] = 0;
                        footerData['total_supply_time'] = 0;
                        footerData['total_else_time'] = 0;

                        voyData.forEach(function(value, key) {
                            let tmpData = data[value];
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

                            realData = [];
                            realData['voy_no'] = value;
                            realData['voy_count'] = tmpData.length;
                            realData['voy_start'] = tmpData[0]['Voy_Date'];
                            realData['voy_end'] = tmpData[tmpData.length - 1]['Voy_Date'];
                            realData['lport'] = cpData[value]['LPort'] == false ? '-' : cpData[value]['LPort'];
                            realData['dport'] = cpData[value]['DPort'] == false ? '-' : cpData[value]['DPort'];
                            realData['sail_time'] = __getTermDay(realData['voy_start'], realData['voy_end'], tmpData[0]['GMT'], tmpData[tmpData.length - 1]['GMT']);

                            // searchObj.setTotalInfo(data);
                            tmpData.forEach(function(data_value, data_key) {
                                total_distance += __parseFloat(data_value["Sail_Distance"]);

                                if(data_key > 0) {
                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_SALING) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_sail_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_LOADING) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        loading_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_DISCH) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        disch_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_WAITING) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_waiting_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_WEATHER) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_weather_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_REPAIR) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_repair_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_SUPPLY) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_supply_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_ELSE) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_else_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                }
                            });

                            realData.total_sail_time = total_sail_time.toFixed(2);
                            realData.total_distance = total_distance;
                            realData.average_speed = BigNumber(realData.total_distance).div(realData.total_sail_time).div(24).toFixed(1);
                            realData.loading_time = loading_time.toFixed(COMMON_DECIMAL);
                            realData.disch_time = disch_time.toFixed(COMMON_DECIMAL);
                            realData.total_loading_time = BigNumber(loading_time).plus(disch_time).plus(total_sail_time).toFixed(2);
                            realData.economic_rate = BigNumber(loading_time).plus(disch_time).plus(realData.total_sail_time).div(realData.sail_time).multipliedBy(100).toFixed(1);
                            realData.total_waiting_time = total_waiting_time.toFixed(COMMON_DECIMAL);
                            realData.total_weather_time = total_weather_time.toFixed(COMMON_DECIMAL);
                            realData.total_repair_time = total_repair_time.toFixed(COMMON_DECIMAL);
                            realData.total_supply_time = total_supply_time.toFixed(COMMON_DECIMAL);
                            realData.total_else_time = total_else_time.toFixed(COMMON_DECIMAL);

                            // Calc Footer data
                            footerData['voy_count'] += parseInt(realData['voy_count']);
                            footerData['sail_time'] += parseInt(realData['sail_time']);
                            footerData['total_distance'] += parseInt(realData['total_distance']);
                            footerData['total_sail_time'] += parseFloat(realData['total_sail_time']);
                            footerData['total_loading_time'] += parseFloat(realData['total_loading_time']);
                            footerData['loading_time'] += parseFloat(realData['loading_time']);
                            footerData['disch_time'] += parseFloat(realData['disch_time']);
                            footerData['total_waiting_time'] += parseFloat(realData['total_waiting_time']);
                            footerData['total_weather_time'] += parseFloat(realData['total_weather_time']);
                            footerData['total_repair_time'] += parseFloat(realData['total_repair_time']);
                            footerData['total_supply_time'] += parseFloat(realData['total_supply_time']);
                            footerData['total_else_time'] += parseFloat(realData['total_else_time']);

                            footerData['average_speed'] = parseFloat(BigNumber(realData['average_speed']).div(voyData.length).toFixed(2));
                            footerData['economic_rate'] = BigNumber(realData['loading_time']).plus(realData['disch_time']).plus(realData['total_sail_time']).div(realData['sail_time']).multipliedBy(100).div(voyData.length).toFixed(1);

                            list.push(realData);
                        });
                        if (list.length > 0) {
                            footerData['voy_start'] = list[0].voy_start;
                            footerData['voy_end'] = list[list.length - 1].voy_end;
                        } else {
                            footerData['voy_start'] = "-";
                            footerData['voy_end'] = "-";
                        }
                        datasets[index] = {};
                        datasets[index].label = ship_name;
                        var percent = _format((footerData['loading_time'] + footerData['disch_time'] + footerData['total_sail_time'])/footerData['sail_time']*100,1);
                        datasets[index].data = [];
                        datasets[index].data[0] = percent;
                        datasets[index].borderColor = color_table[index];
                        datasets[index].backgroundColor = addAlpha(color_table[index],0.8);
                    }
                    labels = [''];
                    drawThirdGraph(labels,datasets);
                }
            });

            $.ajax({
                url: BASE_URL + 'ajax/business/dynamic/multiSearch',
                type: 'post', 
                data: {
                    'year':year_dyn,
                    'shipId':shipids_all,
                },
                success: function(result) {
                    var index = 0;
                    var show_index = 0;
                    var datasets = [];
                    var labels = [];
                    datasets[0] = {};
                    for (index=0;index<shipids_all.length;index++) {
                        var ship_name = ships[shipids_all[index]];
                        var ship_no = shipids_all[index];

                        let data = result[ship_no]['currentData'];
                        let voyData = result[ship_no]['voyData'];
                        let cpData = result[ship_no]['cpData'];

                        let list = [];
                        let realData = [];
                        let footerData = [];
                        footerData['voy_count'] = 0;
                        footerData['voy_count'] = 0;
                        // footerData['voy_start'] = 0;
                        footerData['sail_time'] = 0;
                        footerData['total_distance'] = 0;
                        footerData['total_sail_time'] = 0;
                        footerData['total_loading_time'] = 0;
                        footerData['loading_time'] = 0;
                        footerData['disch_time'] = 0;
                        footerData['total_waiting_time'] = 0;
                        footerData['total_weather_time'] = 0;
                        footerData['total_repair_time'] = 0;
                        footerData['total_supply_time'] = 0;
                        footerData['total_else_time'] = 0;

                        voyData.forEach(function(value, key) {
                            let tmpData = data[value];
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

                            realData = [];
                            realData['voy_no'] = value;
                            realData['voy_count'] = tmpData.length;
                            realData['voy_start'] = tmpData[0]['Voy_Date'];
                            realData['voy_end'] = tmpData[tmpData.length - 1]['Voy_Date'];
                            realData['lport'] = cpData[value]['LPort'] == false ? '-' : cpData[value]['LPort'];
                            realData['dport'] = cpData[value]['DPort'] == false ? '-' : cpData[value]['DPort'];
                            realData['sail_time'] = __getTermDay(realData['voy_start'], realData['voy_end'], tmpData[0]['GMT'], tmpData[tmpData.length - 1]['GMT']);

                            // searchObj.setTotalInfo(data);
                            tmpData.forEach(function(data_value, data_key) {
                                total_distance += __parseFloat(data_value["Sail_Distance"]);

                                if(data_key > 0) {
                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_SALING) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_sail_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_LOADING) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        loading_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_DISCH) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        disch_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_WAITING) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_waiting_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_WEATHER) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_weather_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_REPAIR) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_repair_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_SUPPLY) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_supply_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                    if(data_value['Voy_Type'] == DYNAMIC_SUB_ELSE) {
                                        let preKey = data_key - 1;
                                        let start_date = tmpData[preKey]['Voy_Date'] + ' ' + tmpData[preKey]['Voy_Hour'] + ':' + tmpData[preKey]['Voy_Minute'];
                                        let end_date = data_value['Voy_Date'] + ' ' + data_value['Voy_Hour'] + ':' + data_value['Voy_Minute'];
                                        total_else_time += __getTermDay(start_date, end_date, tmpData[preKey]['GMT'], data_value['GMT']);
                                    }

                                }
                            });

                            realData.total_sail_time = total_sail_time.toFixed(2);
                            realData.total_distance = total_distance;
                            realData.average_speed = BigNumber(realData.total_distance).div(realData.total_sail_time).div(24).toFixed(1);
                            realData.loading_time = loading_time.toFixed(COMMON_DECIMAL);
                            realData.disch_time = disch_time.toFixed(COMMON_DECIMAL);
                            realData.total_loading_time = BigNumber(loading_time).plus(disch_time).plus(total_sail_time).toFixed(2);
                            realData.economic_rate = BigNumber(loading_time).plus(disch_time).plus(realData.total_sail_time).div(realData.sail_time).multipliedBy(100).toFixed(1);
                            realData.total_waiting_time = total_waiting_time.toFixed(COMMON_DECIMAL);
                            realData.total_weather_time = total_weather_time.toFixed(COMMON_DECIMAL);
                            realData.total_repair_time = total_repair_time.toFixed(COMMON_DECIMAL);
                            realData.total_supply_time = total_supply_time.toFixed(COMMON_DECIMAL);
                            realData.total_else_time = total_else_time.toFixed(COMMON_DECIMAL);

                            // Calc Footer data
                            footerData['voy_count'] += parseInt(realData['voy_count']);
                            footerData['sail_time'] += parseInt(realData['sail_time']);
                            footerData['total_distance'] += parseInt(realData['total_distance']);
                            footerData['total_sail_time'] += parseFloat(realData['total_sail_time']);
                            footerData['total_loading_time'] += parseFloat(realData['total_loading_time']);
                            footerData['loading_time'] += parseFloat(realData['loading_time']);
                            footerData['disch_time'] += parseFloat(realData['disch_time']);
                            footerData['total_waiting_time'] += parseFloat(realData['total_waiting_time']);
                            footerData['total_weather_time'] += parseFloat(realData['total_weather_time']);
                            footerData['total_repair_time'] += parseFloat(realData['total_repair_time']);
                            footerData['total_supply_time'] += parseFloat(realData['total_supply_time']);
                            footerData['total_else_time'] += parseFloat(realData['total_else_time']);

                            footerData['average_speed'] = parseFloat(BigNumber(realData['average_speed']).div(voyData.length).toFixed(2));
                            footerData['economic_rate'] = BigNumber(realData['loading_time']).plus(realData['disch_time']).plus(realData['total_sail_time']).div(realData['sail_time']).multipliedBy(100).div(voyData.length).toFixed(1);

                            list.push(realData);
                        });
                        // +
                        var voy_rate;
                        if (footerData['voy_count'] == 0) voy_rate = 0;
                        else voy_rate = footerData['sail_time'] / footerData['voy_count'] * 100;
                        var row_html = "<tr class='" + ((index%2==0)?"member-item-odd":"member-item-even") + "'>" + "<td class='center'>" + ship_name + "</td><td class='center'>" + footerData['voy_count'] + "</td><td class='center'>" + voy_rate.toFixed(1) + " %</td><tr>";
                        $('#dyn-body').append(row_html);
                    }
                }
            });

            $.ajax({
                url: BASE_URL + 'ajax/shipmanage/ctm/debits',
                type: 'post',
                data: {
                    year:year_graph,
                    shipId:shipids_all,
                },
                success: function(data) {
                    var debits_sum = [];
                    for(var i=0;i<12;i++) debits_sum[i] = 0;
                    var index = 0;
                    var datasets = [];
                    for (index=0;index<shipids_graph.length;index++) {
                        var ship_name = ships[shipids_graph[index]];
                        var ship_no = shipids_graph[index];
                        datasets[index] = {};
                        datasets[index].label = ship_name;
                        datasets[index].data = [];
                        datasets[index].borderColor = color_table[index];
                        datasets[index].backgroundColor = addAlpha(color_table[index],0.8);
                        for(var i=1;i<12;i++) {
                            var offset;
                            if (i == 0) offset = 1;
                            else if (i == 1) offset = 3;
                            else if (i == 2) offset = 4;
                            else if (i == 3) offset = 6;
                            else if (i == 4) offset = 7;
                            else if (i == 5) offset = 8;
                            else if (i == 6) offset = 11;
                            else if (i == 7) offset = 12;
                            else if (i == 8) offset = 2;
                            else if (i == 9) offset = 5;
                            else if (i == 10) offset = 9;
                            else if (i == 11) offset = 10;
                            datasets[index].data[i] = data[ship_no]['total'][offset];
                        }
                    }
                    drawFifthGraph(datasets);
                }
            })
        }

        function drawFirstGraph(datasets) {
            $('#graph_first').html('');
            $('#graph_first').append('<canvas id="first-chart" height="400" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("first-chart"), {
                type: 'line',
                data: {
                    labels: ['1月','2月','3月','4月','6月','7月','8月','9月','10月','11月','12月'],
                    datasets: datasets
                },
                options: {
                    title: {
                    display: true,
                    text: ''
                    },
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                    }
                }
            });
        }
        function drawSecondGraph(datasets) {
            $('#graph_second').html('');
            $('#graph_second').append('<canvas id="second-chart" height="250" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("second-chart"), {
                type: 'bar',
                data: {
                    labels: ['收入','支出'],
                    datasets: datasets
                },
                options: {
                    indexAxis: 'y',
                    elements: {
                        bar: {
                            borderWidth: 2,
                        }
                        },
                        responsive: true,
                        plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                        }
                    }
                }
            });
        }
        function drawThirdGraph(labels,datasets) {
            $('#graph_third').html('');
            $('#graph_third').append('<canvas id="third-chart" height="250" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("third-chart"), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                    }
                }
            });
        }
        function drawFourthGraph(datasets) {
            $('#graph_fourth').html('');
            $('#graph_fourth').append('<canvas id="fourth-chart" height="500" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("fourth-chart"), {
                type: 'bar',
                data: {
                    labels: ['油款','港费','劳务费','CTM','其他','工资','伙食费','物料费','修理费','管理费','保险费','检验费','证书费'],
                    datasets: datasets
                },
                options: {
                    indexAxis: 'y',
                    elements: {
                        bar: {
                            borderWidth: 2,
                        }
                        },
                        responsive: true,
                        plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                        }
                    }
                }
            });
        }
        function drawFifthGraph(datasets) {
            $('#graph_fifth').html('');
            $('#graph_fifth').append('<canvas id="fifth-chart" height="500" class="chartjs-demo"></canvas>');
            new Chart(document.getElementById("fifth-chart"), {
                type: 'bar',
                data: {
                    labels: ['劳务费','娱乐费','招待费','奖励','小费','通信费','其他','伙食费','物料费','修理费','证书费'],
                    datasets: datasets
                },
                options: {
                    indexAxis: 'y',
                    elements: {
                        bar: {
                            borderWidth: 2,
                        }
                        },
                        responsive: true,
                        plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                        }
                    }
                }
            });
        }
    </script>

    <script>

        var certObj = new Vue({
            el: '#cert-body',
            data: {
                list: [],
            }
        })

        $(".sign_list").slick({
            dots: false,
            vertical: true,
            centerMode: false,
            autoplay: true,
            prevArrow: false,
            nextArrow: false,
            autoplaySpeed: 2000,
            swipe: false,
            slidesToShow: 1,
            slidesToScroll: 1
        });

    </script>
    <script type="text/javascript">
        var DYNAMIC_SUB_SALING = '{!! DYNAMIC_SUB_SALING !!}';
        var DYNAMIC_SUB_LOADING = '{!! DYNAMIC_SUB_LOADING !!}';
        var DYNAMIC_SUB_DISCH = '{!! DYNAMIC_SUB_DISCH !!}';
        var DYNAMIC_SUB_WAITING = '{!! DYNAMIC_SUB_WAITING !!}';
        var DYNAMIC_SUB_WEATHER = '{!! DYNAMIC_SUB_WEATHER !!}';
        var DYNAMIC_SUB_REPAIR = '{!! DYNAMIC_SUB_REPAIR !!}';
        var DYNAMIC_SUB_SUPPLY = '{!! DYNAMIC_SUB_SUPPLY !!}';
        var DYNAMIC_SUB_ELSE = '{!! DYNAMIC_SUB_ELSE !!}';

        
        var DYNAMIC_SAILING = '{!! DYNAMIC_SAILING !!}';
        var DYNAMIC_CMPLT_DISCH = '{!! DYNAMIC_CMPLT_DISCH !!}';
        const DAY_UNIT = 1000 * 3600;
        const COMMON_DECIMAL = 2;

        function addAlpha(color, opacity) {
            const _opacity = Math.round(Math.min(Math.max(opacity || 1, 0), 1) * 255);
            return color + _opacity.toString(16).toUpperCase();
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

        function _format(value, decimal = 2) {
            return isNaN(value) || value == 0 || value == null || value == undefined ? '' : number_format(value, decimal);
        }

    </script>
@endsection
