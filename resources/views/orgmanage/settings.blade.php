@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
?>

@section('styles')
<link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/multiselect.css') }}" rel="stylesheet"/>
    <script src="{{ cAsset('assets/js/multiselect.min.js') }}"></script>
@endsection
@section('scripts')
<link href="{{ cAsset('assets/js/chartjs/chartist.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ cAsset('assets/js/chartjs/c3.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ cAsset('assets/js/chartjs/flot.css') }}">
    
    <script src="{{ cAsset('assets/js/chartjs/chartist.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/chartjs.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/d3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/c3.js') }}"></script>
    <script src="{{ cAsset('assets/js/chartjs/flot.js') }}"></script>
@endsection
@section('content')
    <div class="main-content">
        <style>
            .settings-item {
                height:30px;
            }

            .add-td-label {
                font-size:12px!important;
                font-weight:bold!important;
                background-color:#d9f8fb !important;
                text-align: left!important;
                padding-left:5px!important;
            }

            .add-td-text {
                background-color: #FFFFFF;
                font-weight: normal;
                vertical-align: middle;
            }

            .add-td-input {
                font-size:14px!important;
                margin-left:10px;
            }

            .add-td-select {
                font-size:14px!important;
                margin-left:5px;
                margin-right:10px;
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
        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>看板管理</b></h4>
                </div>
            </div>
            <div class="row col-md-12">
                <div class="col-md-6" style="padding:unset!important">
                </div>
                <div class="col-md-6" style="padding:unset!important">
                    <div class="btn-group f-right">
                        <a id="btnSave" class="btn btn-sm btn-success" style="width: 80px">
                            <i class="icon-save"></i>{{ trans('common.label.save') }}
                        </a>
                    </div>
                </div>
            </div>
            <form id="validation-form" action="updateSettings" role="form" method="POST" enctype="multipart/form-data">
            <div class="col-md-12" style="margin-top:4px;">
                <div id="item-manage-dialog" class="hide"></div>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="row">
                    <div class="common-list" style="">
                        <table style="table-layout:fixed;">
                            <tbody class="" id="list-body">
                                <tr>
                                    <td class="add-td-label" style="width:20%!important;height:20px;">GRAPH:</td>
                                    <td class="add-td-text" style="width:20%!important;">
                                        <select name="select-graph-year" id="select-graph-year" class="form-control" style="width:100%;border:unset!important;">
                                            @for($i=date("Y");$i>=$start_year;$i--)
                                            <option value="{{$i}}" @if($i==$settings['graph_year']) selected @endif>{{$i}}年</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="add-td-text">
                                        <select name="select-graph-ship[]" id="select-graph-ship" style="z-index:10000!important;" class="custom-select d-inline-block form-control" multiple>
                                            @foreach($shipList as $ship)
                                                <option value="{{ $ship['IMO_No'] }}" data-name="{{$ship['shipName_En']}}">{{$ship['NickName']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" style="width:20%!important;">证书到期(提前):</td>
                                    <td class="add-td-text" colspan="2">
                                        <select class="form-control" name="cert-expire_date" id="cert-expire_date">
                                            <option value="0" @if($settings['cert_expire_date']=='0') selected @endif>All</option>
                                            <option value="90" @if($settings['cert_expire_date']=='90') selected @endif>90天</option>
                                            <option value="120" @if($settings['cert_expire_date']=='120') selected @endif>120天</option>
                                            <option value="180" @if($settings['cert_expire_date']=='180') selected @endif>180天</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label">批准次数:</td>
                                    <td class="add-td-text" colspan="2">
                                        <select name="select-report-year" id="select-report-year" class="form-control" style="font-size:13px">
                                            @for($i=date("Y");$i>=$start_year;$i--)
                                            <option value="{{$i}}" @if($i==$settings['report_year']) selected @endif>{{$i}}年</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label">船舶日报:</td>
                                    <td class="add-td-text" colspan="2">
                                        <select name="select-dyn-year" id="select-dyn-year" class="form-control" style="font-size:13px">
                                            @for($i=date("Y");$i>=$start_year;$i--)
                                            <option value="{{$i}}" @if($i==$settings['dyn_year']) selected @endif>{{$i}}年</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="head-fix-div" style="height: 157px;margin-top:20px;">
                        <table id="table-shipmember-list" style="table-layout:fixed;">
                            <thead class="">
                                <th class="text-center style-normal-header" style="width: 9%;height:35px;box-shadow: inset 0 -1px #000, 1px -1px #000;"><span>审批编号</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>申请日期</span></th>
                                <th class="text-center style-normal-header" style="width: 70%;"colspan="5"><span>等待凭证</span></th>
                                <th class="text-center style-normal-header"><span>无显示</span></th>
                            </thead>
                            <tbody class="" id="list-body" style="">
                            @if (isset($noattachments) && count($noattachments) > 0)
                            <?php $index = 1;?>
                            @foreach ($noattachments as $report)
                                <?php $nickName=""?>
                                @foreach($shipList as $ship)
                                    @if ($ship->IMO_No == $report['shipNo'])
                                    <?php $nickName = $ship['NickName'];?>
                                    @endif
                                @endforeach
                                <tr @if($index%2==0) class="member-item-odd" @else class="member-item-even" @endif>
                                    <td class="center" style="height:20px;">{{$report['report_id']}}</td>
                                    <td class="center">{{$report['report_date']}}</td>
                                    <td class="center">{{g_enum('ReportTypeData')[$report['flowid']]}}</td>
                                    <td class="center">{{$nickName}}</td>
                                    <td class="center">{{$report['voyNo']}}</td>
                                    <td class="center">{{isset(g_enum('FeeTypeData')['Debit'][$report['profit_type']])?g_enum('FeeTypeData')['Debit'][$report['profit_type']]:""}}</td>
                                    <td class="center">{{$report['report_date']}}</td>
                                    <td class="center report-visible" style="cursor:pointer;">{{$report['isvisible']?"✓":""}}</td>
                                    <?php $index++;?>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8">{{ trans('common.message.no_data') }}</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                        </div>
                        <div class="table-head-fix-div" style="max-height: 157px;margin-top:20px;">
                        <table id="table-shipmember-list" style="table-layout:fixed;">
                            <thead class="">
                                <th class="text-center style-normal-header" style="width: 10%;height:35px;"><span>无显示</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>船名</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>DATE</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>TIME</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>STATUS</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>POSITION</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>CGO QTY</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>ROB(FO)</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>ROB(DO)</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>BNKR(FO)</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>BNKR(DO)</span></th>
                                <th class="text-center style-normal-header" style="width: 10%;"><span>REMARK</span></th>
                            </thead>
                            <tbody class="" id="list-body">
                            @if (isset($voyList) && count($voyList) > 0)
                            <?php $index = 1;?>
                            
                            @foreach ($voyList as $info)
                                <?php $nickName=""?>
                                @foreach($shipList as $ship)
                                    @if ($ship->IMO_No == $info['Ship_ID'])
                                    <?php $nickName = $ship['NickName'];?>
                                    @endif
                                @endforeach
                                <tr @if($index%2==0) class="member-item-odd" @else class="member-item-even" @endif>
                                    <td class="center dyn-visible" data-id="{{$info['id']}}" style="cursor:pointer;">{{$info['isvisible']?"✓":""}}</td>
                                    <td class="center" style="height:20px;">{{$nickName}}</td>
                                    <td class="center">{{$info['Voy_Date']}}</td>
                                    <td class="center">{{str_pad($info['Voy_Hour'],2,"0",STR_PAD_LEFT).str_pad($info['Voy_Minute'],2,"0",STR_PAD_LEFT)}}</td>
                                    <td class="center">{{g_enum('DynamicStatus')[$info['Voy_Status']][0]}}</td>
                                    <td class="center">{{$info['Ship_Position']}}</td>
                                    <td class="center">{{$info['Cargo_Qtty']}}</td>
                                    <td class="center">{{$info['ROB_FO']}}</td>
                                    <td class="center">{{$info['ROB_DO']}}</td>
                                    <td class="center">{{$info['BUNK_FO']}}</td>
                                    <td class="center">{{$info['BUNK_DO']}}</td>
                                    <td class="center">{{$info['Remark']}}</td>
                                    <?php $index++;?>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8">{{ trans('common.message.no_data') }}</td>
                            </tr>
                            
                            @endif
                            
                            </tbody>
                        </table>
                        </div>
                        <table id="table-shipmember-list" style="table-layout:fixed;margin-top:20px;">
                            <thead class="">
                                <th class="text-center style-normal-header" style="width: 5%;height:35px;"><span>Order No</span></th>
                                <th class="text-center style-normal-header" style="width: 60%;"><span>网站地址</span></th>
                                <th class="text-center style-normal-header" style="width: 30%;"><span>有关网站</span></th>
                            </thead>
                            <tbody class="" id="list-body">
                            @for($index=0;$index<10;$index++)
                                <tr @if($index%2==0) class="member-item-odd" @else class="member-item-even" @endif>
                                @if (isset($sites[$index]))
                                    <td class="center" style="height:20px;"><input name="site_orders[]" @if($index%2==0) class="text-center form-control member-item-odd" @else class="text-center form-control member-item-even" @endif value="{{$sites[$index]['orderNo']}}"></input></td>
                                    <td class="center"><input name="site_links[]" @if($index%2==0) class="form-control member-item-odd" @else class="form-control member-item-even" @endif value="{{$sites[$index]['link']}}"></input></td>
                                    @if ($sites[$index]['image'] != null)
                                    <td class="center">
                                        <div class="report-attachment">
                                            <a href="{{$sites[$index]['image']}}" target="_blank">
                                                <img src="{{ cAsset('assets/images/document.png') }}" width="15" height="15">
                                            </a>
                                            <img src="{{ cAsset('assets/images/cancel.png') }}" onclick="deleteAttach(this)" width="10" height="10">
                                            <label for={{$index}} ><img src="{{ cAsset('assets/images/paper-clip.png') }}"  width="15" height="15" style="margin: 2px 4px" class="d-none"></label>
                                            <input type="file" name="attachment[]" id="{{$index}}" data-index="{{$index}}" accept="image/png, image/gif, image/jpeg" class="d-none" enctype="multipart/form-data">
                                            <input type="hidden" name="is_update[]" class="d-none" value="0">
                                        </div>
                                    </td>
                                    @else
                                    <td class="center">
                                        <label for={{$index}} ><img src="{{ cAsset('assets/images/paper-clip.png') }}"  width="15" height="15" style="margin: 2px 4px"></label>
                                        <input type="file" name="attachment[]" id="{{$index}}" data-index="{{$index}}" accept="image/png, image/gif, image/jpeg" class="d-none" enctype="multipart/form-data">
                                        <input type="hidden" name="is_update[]" class="d-none" value="0">
                                    </td>
                                    @endif
                                @else
                                    <td class="center" style="height:20px;"><input name="site_orders[]" @if($index%2==0) class="text-center form-control member-item-odd" @else class="text-center form-control member-item-even" @endif value=""></input></td>
                                    <td class="center"><input name="site_links[]" @if($index%2==0) class="form-control member-item-odd" @else class="form-control member-item-even" @endif value=""></input></td>
                                    <td class="center">
                                        <label for={{$index}} ><img src="{{ cAsset('assets/images/paper-clip.png') }}"  width="15" height="15" style="margin: 2px 4px"></label>
                                        <input type="file" name="attachment[]" id="{{$index}}" data-index="{{$index}}" accept="image/png, image/gif, image/jpeg" class="d-none" enctype="multipart/form-data">
                                        <input type="hidden" name="is_update[]" class="d-none" value="0">
                                    </td>
                                @endif
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                        <div class="space-12"></div>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <audio controls="controls" class="d-none" id="warning-audio">
            <source src="{{ cAsset('assets/sound/delete.wav') }}">
            <embed src="{{ cAsset('assets/sound/delete.wav') }}" type="audio/wav">
        </audio>
    </div>
    <script src="{{ cAsset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/x-editable/ace-editable.min.js') }}"></script>
    <script src="{{ cAsset('assets/js/jsquery.dataTables.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.rowsGroup.js') }}"></script>
    <script src="{{ cAsset('assets/js/bignumber.js') }}"></script>
    <?php
	echo '<script>';
    echo 'var ship_ids = ' . $settings['graph_ship'] . ';';
	echo '</script>';
	?>
    <script>
        var token = '{!! csrf_token() !!}';
        var shipName = '';

        document.multiselect('#select-graph-ship')
        .setCheckBoxClick("checkboxAll", function(target, args) {
        })
        .setCheckBoxClick("1", function(target, args) {
        });
        $('.multiselect-input').attr('style','border:unset!important;width:100%!important;height:10px!important;margin-top:3px;width:auto!important;');
        $('.multiselect-count').attr('style','margin-top:-4px!important;');
        $('.multiselect-input-div').attr('style','border:unset!important;height:10px!important;width:auto!important;');
        $('.multiselect-dropdown-arrow').attr('style','margin-top:1px');
        $('.multiselect-list').attr('style','margin-top:7px;z-index:1000;')
        $('.multiselect-wrapper').on('click', function() {
            $('.multiselect-wrapper hr').hide()
        })

        $("#btnSave").on('click', function() {
            $('#validation-form').submit();
        });

        for (var i=0;i<ship_ids.length; i++) {
            document.multiselect('#select-graph-ship').select(ship_ids[i]);
        }
        $('#select-graph-ship').trigger("chosen:updated");


        $("input[type=file]").on('change',function(e) {
            var parentElement = e.target.parentElement;
            var dest = ($($(parentElement).children(":first"))).children(":first");
            var isUpdate = $(parentElement).children().eq(2);
            $(dest).attr('src', "{{ cAsset('assets/images/document.png') }}");
            $(isUpdate).val('1');
        });

        $(".report-visible").on('click',function(e) {
            var isvisible = 0;
            var td_html = "";
            if (e.target.innerHTML == "") {
                isvisible = 1;
                td_html = "✓";
            }
            e.target.innerHTML = td_html;

            //td_html = "input type='hidden' name="
            var parentElement = e.target.parentElement;
            var name = $($(parentElement).children(":last")).attr('name');
            if (name == 'visible_value[]') {
                $(parentElement).children(":last").remove();
                $(parentElement).children(":last").remove();
            }
            td_html = "<input type='hidden' name='visible_id[]' value='" + ($(parentElement).children(":first")).html() + "'/>";
            td_html += "<input type='hidden' name='visible_value[]' value='" + isvisible + "'/>";
            $(parentElement).append(td_html);
        });

        $(".dyn-visible").on('click',function(e) {
            var isvisible = 0;
            var td_html = "";
            if (e.target.innerHTML == "") {
                isvisible = 1;
                td_html = "✓";
            }
            e.target.innerHTML = td_html;

            //td_html = "input type='hidden' name="
            var parentElement = e.target.parentElement;
            var name = $($(parentElement).children(":last")).attr('name');
            if (name == 'dyn_value[]') {
                $(parentElement).children(":last").remove();
                $(parentElement).children(":last").remove();
            }
            td_html = "<input type='hidden' name='dyn_id[]' value='" + ($(parentElement).children(":first")).attr('data-id') + "'/>";
            td_html += "<input type='hidden' name='dyn_value[]' value='" + isvisible + "'/>";
            $(parentElement).append(td_html);
        });

        function alertAudio() {
            document.getElementById('warning-audio').play();
        }

        function deleteAttach(e) {
            var parentElement = e.parentElement;
            $($(parentElement).children(":first")).remove();
            $($(parentElement).children(":first")).remove();
            $($(parentElement).children(":first")).attr('class','d-block');
            ($($(parentElement).children(":first"))).children(":first").attr('class','d-block');
            var isUpdate = $(parentElement).children().eq(2);
            $(isUpdate).val('1');
        }

        function onFileChange(e) {
            alert(e);
        }
    </script>

@endsection
