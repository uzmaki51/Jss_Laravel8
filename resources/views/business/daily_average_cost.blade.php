@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
$ships = Session::get('shipList');
?>
@section('styles')
    <link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/>
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
        <div class="page-content">
            <div class="page-header">
                <div class="col-sm-3">
                    <h4><b>日均成本</b></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-7">
                        <label class="custom-label d-inline-block font-bold" style="padding: 6px;">船名:</label>
                        <select class="custom-select d-inline-block" id="select-table-ship" style="width:80px">
                            <!--option value="" selected></option-->
                            <?php $index = 0 ?>
                            @foreach($shipList as $ship)
                                <?php $index ++ ?>
                                <option value="{{ $ship['IMO_No'] }}" @if(isset($shipId) && ($shipId == $ship['IMO_No'])) selected @endif data-name="{{$ship['shipName_En']}}">{{$ship['NickName']}}</option>
                            @endforeach
                        </select>
                        <strong class="f-right" style="font-size: 16px; padding-top: 6px;"><span id="table_info"></span>最新三年数据</strong>
                    </div>
                    <div class="col-md-5" style="padding:unset!important">
                        <div class="btn-group f-right">
                            <a id="btnSave" class="btn btn-sm btn-success" style="width: 80px">
                                <i class="icon-save"></i>{{ trans('common.label.save') }}
                            </a>
                            <a onclick="javascript:fnExcelTableReport();" class="btn btn-warning btn-sm excel-btn">
                                <i class="icon-table"></i>{{ trans('common.label.excel') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:4px;">
                    <div id="item-manage-dialog" class="hide"></div>
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="row">
                        <div class="table-head-fix-div" id="div-income-expense" style="height: 700px">
                            <table id="table-income-expense-list" style="max-width:unset!important;table-layout:fixed;width:1500px!important;">
                                <thead class="">
                                <tr>
                                    <th class="text-center style-normal-header" rowspan="2" style="width: 2.5%;"><span>年</span></th>
                                    <th class="text-center style-normal-header" rowspan="2" style="width: 3%;"><span>航次用时</span></th>
                                    <th class="text-center style-normal-header" rowspan="2" style="width: 3%;"><span>VOY</span></th>
                                    <th class="text-center style-normal-header" rowspan="2" style="width: 3%;"><span>TC</span></th>
                                    <th class="text-center style-normal-header" rowspan="2" style="width: 3%;"><span>NON</span></th>
                                    <th class="text-center style-normal-header" rowspan="2" style="width: 5.5%;"><span>收入</span></th>
                                    <th class="text-center style-normal-header" rowspan="2" style="width: 5.5%;"><span>支出</span></th>
                                    <th class="text-center style-normal-header" colspan="13"><span>支出分类 ($)</span></th>
                                </tr>
                                <tr>
                                    <th class="text-center style-red-header" style="width: 4%;"><span>油款</span></th>
                                    <th class="text-center style-red-header" style="width: 4%;"><span>港费</span></th>
                                    <th class="text-center style-red-header" style="width: 4%;"><span>劳务费</span></th>
                                    <th class="text-center style-red-header" style="width: 4%;"><span>CTM</span></th>
                                    <th class="text-center style-red-header" style="width: 4%;"><span>其他</span></th>
                                    <th class="text-center style-normal-header" style="width: 4%;"><span>工资</span></th>
                                    <th class="text-center style-normal-header" style="width: 4%;"><span>伙食费</span></th>
                                    <th class="text-center style-normal-header" style="width: 4%;"><span>物料费</span></th>
                                    <th class="text-center style-normal-header" style="width: 4%;"><span>修理费</span></th>
                                    <th class="text-center style-normal-header" style="width: 4%;"><span>管理费</span></th>
                                    <th class="text-center style-normal-header" style="width: 4%;"><span>保险费</span></th>
                                    <th class="text-center style-normal-header" style="width: 4%;"><span>检验费</span></th>
                                    <th class="text-center style-normal-header" style="width: 4%;"><span>证书费</span></th>
                                </tr>
                                </thead>
                                <tbody class="" id="table-income-expense-body">
                                </tbody>
                            </table>
                            <div class="space-12"></div>
                            <div class="col-md-6">
                                <strong class="f-right" style="font-size: 16px; padding-top: 6px; padding-bottom:8px;"><span id="costs_info"></span>日均成本</strong>
                            </div>
                            <form id="form-costs-list" action="updateCostInfo" role="form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <table id="table-expect-cost" style="table-layout:fixed;width:900px!important;">
                                <thead class="">
                                <tr>
                                    <th class="text-center style-normal-header" rowspan="2"><span></span></th>
                                    <th class="text-center style-normal-header" colspan="8"><span>管理成本</span></th>
                                </tr>
                                <tr>
                                    <th class="text-center style-normal-header"><span>工资</span></th>
                                    <th class="text-center style-normal-header"><span>伙食费</span></th>
                                    <th class="text-center style-normal-header"><span>物料费</span></th>
                                    <th class="text-center style-normal-header"><span>修理费</span></th>
                                    <th class="text-center style-normal-header"><span>管理费</span></th>
                                    <th class="text-center style-normal-header"><span>保险费</span></th>
                                    <th class="text-center style-normal-header"><span>检验费</span></th>
                                    <th class="text-center style-normal-header"><span>证书费</span></th>
                                </tr>
                                </thead>
                                <tbody class="" id="">
                                <tr>
                                    <td class="text-center style-normal-header" style="background:#d9f8fb!important;"><span>年份</span></td>
                                    <td class="disable-td"><input type="text" name="output[]" class="form-control disabled-td text-center" value="" style="background:#ececec;width: 100%" readonly></td>
                                    <td class="disable-td"><input type="text" name="output[]" class="form-control disabled-td text-center" value="" style="background:#ececec;width: 100%" readonly></td>
                                    <td class="disable-td"><input type="text" name="output[]" class="form-control disabled-td text-center" value="" style="background:#ececec;width: 100%" readonly></td>
                                    <td class="disable-td"><input type="text" name="output[]" class="form-control disabled-td text-center" value="" style="background:#ececec;width: 100%" readonly></td>
                                    <td class="disable-td"><input type="text" name="output[]" class="form-control disabled-td text-center" value="" style="background:#ececec;width: 100%" readonly></td>
                                    <td class="white-bg"><input type="text" name="input[]"  class="form-control disabled-td text-center" value="{{ $costs['input1'] }}" style="width: 100%"></td>
                                    <td class="white-bg"><input type="text" name="input[]"  class="form-control disabled-td text-center" value="{{ $costs['input2'] }}" style="width: 100%"></td>
                                    <td class="white-bg"><input type="text" name="input[]"  class="form-control disabled-td text-center" value="{{ $costs['input3'] }}" style="width: 100%"></td>
                                </tr>
                                <tr>
                                    <td class="text-center style-normal-header" style="background:#d9f8fb!important;"><span>月份</span></td>
                                    <td><input type="text" name="input[]"  class="form-control disabled-td text-center" value="{{ $costs['input4'] }}" style="width: 100%"></td>
                                    <td><input type="text" name="input[]"  class="form-control disabled-td text-center" value="{{ $costs['input5'] }}" style="width: 100%"></td>
                                    <td><input type="text" name="input[]"  class="form-control disabled-td text-center" value="{{ $costs['input6'] }}" style="width: 100%"></td>
                                    <td><input type="text" name="input[]"  class="form-control disabled-td text-center" value="{{ $costs['input7'] }}" style="width: 100%"></td>
                                    <td><input type="text" name="input[]"  class="form-control disabled-td text-center" value="{{ $costs['input8'] }}" style="width: 100%"></td>
                                    <td class="disable-td"><input type="text" name="output[]"  class="form-control disabled-td text-center" value="" style="background:#ececec;width: 100%" readonly></td>
                                    <td class="disable-td"><input type="text" name="output[]"  class="form-control disabled-td text-center" value="" style="background:#ececec;width: 100%" readonly></td>
                                    <td class="disable-td"><input type="text" name="output[]"  class="form-control disabled-td text-center" value="" style="background:#ececec;width: 100%" readonly></td>
                                </tr>
                                <tr style="height:30px;border:2px solid black;">
                                    <td class="text-center style-normal-header" style="background:#d9f8fb!important;"><span>日成本</span></td>
                                    <td colspan="8" class="sub-small-header style-normal-header text-center" id="total-sum">333</td>
                                </tbody>
                            </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="keep_list" name="keep_list"></input>
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
    echo 'var now_year = ' . date("Y") . ';';
    echo 'var FeeTypeData = ' . json_encode(g_enum('FeeTypeData')) . ';';
	echo '</script>';
	?>

    <script>
        var submitted = false;
        $("#btnSave").on('click', function() {
            var input = $("<input>").attr("type", "hidden").attr("name", "select-ship").val(shipid_table);
            $('#form-costs-list').append(input);

            submitted = true;
            $('#form-costs-list').submit();
        });

        var $form = $('form');
        var origForm = "";
        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                                    + 'If you leave before saving, your changes will be lost.';
            var newForm = $form.serialize();
            if ((newForm !== origForm) && !submitted) {
                (e || window.event).returnValue = confirmationMessage;
            }
            return confirmationMessage;
        });

        function setValues()
        {
            var inputs = $('input[name="input[]"]');
            var outputs = $('input[name="output[]"]');
            var total_sum = 0;
            for (var i=0;i<inputs.length;i++) {
                var value = inputs[i].value;
                value = value.replace("$","").replace(",","");
                var value = parseFloat(value);
                if (i < 3) {
                    total_sum += value;
                    value = value / 12;
                } else {
                    value = value * 12;
                    total_sum += value;
                }
                if (!isNaN(value) && value != "" && value != null) {
                    outputs[(i+5)%8].value = '$' + prettyValue(value);
                }
            }
            if (!isNaN(total_sum) && total_sum != "" && total_sum != null) {
                $('#total-sum').html('$' + prettyValue(total_sum));
            }
            else {
                $('#total-sum').html('-');
            }

            if (origForm == "")
                origForm = $form.serialize();
        }
        $('input[name="input[]"]').on('keyup', function(evt) {
            setValues();
        });

        $('input[name="input[]"]').on('keydown', function(evt) {
            if (evt.key == "Enter") {
                if (evt.target.value == '') return;
                var val = evt.target.value.replace(',','').replace('$','');
                $(evt.target).val('$' + prettyValue(val));
            }
        });
        setValues();

        $('body').on('keydown', 'input', function(e) {
            if (e.key === "Enter") {
                var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
                focusable = form.find('input[name="input[]"]').filter(':visible');
                next = focusable.eq(focusable.index(this)+1);
                if (next.length) {
                    next.focus();
                    next.select();
                }
                return false;
            }
        });
/*
        $('input[name="input[]"]').on('change', function(evt) {
            if (evt.target.value == '') return;
            var val = evt.target.value.replace(',','').replace('$','');
            $(evt.target).val('$' + prettyValue(val));
        });
*/
        $('input[name="input[]"]').on('focus', function(evt) {
            $(evt.target).val($(evt.target).val().replace(',','').replace('$',''));
        });

        $('#table_info').html('"' + $("#select-table-ship option:selected").attr('data-name') + '"');
        $('#costs_info').html('"' + $("#select-table-ship option:selected").attr('data-name') + '"');

        var token = '{!! csrf_token() !!}';
        var shipid_table;
        var listTable = null;
        var table_sums = [];
        var dest_obj;

        shipid_table = $("#select-table-ship").val();
        initTable();

        function initTable() {
            listTable = $('#table-income-expense-list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                bAutoWidth: false, 
                ajax: {
                    url: BASE_URL + 'ajax/operation/listByShipForPast',
                    type: 'POST',
                    data: {'shipId':shipid_table},
                },
                "ordering": false,
                "pageLength": 500,
                columnDefs: [
                ],
                columns: [
                    {data: 'year', className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                    {data: null, className: "text-center"},
                ],
                createdRow: function (row, data, index) {
                    console.log(data);
                    if ((index%2) == 0)
                        $(row).attr('class', 'cost-item-even');
                    else
                        $(row).attr('class', 'cost-item-odd');

                    if (data['max_date'] != false && data['min_date'] != false) {
                        var start_date = data['min_date'].Voy_Date + ' ' + data['min_date'].Voy_Hour + ':' + data['min_date'].Voy_Minute;
                        var end_date = data['max_date'].Voy_Date + ' ' + data['max_date'].Voy_Hour + ':' + data['max_date'].Voy_Minute;

                        var sail_time = __getTermDay(start_date, end_date, data['min_date'].GMT, data['max_date'].GMT);
                        $('td', row).eq(1).html(sail_time.toFixed(2));
                    }
                    else
                    {
                        $('td', row).eq(1).html('-');
                    }

                    if (data['VOY_count'] != null) {
                        $('td', row).eq(2).html(data['VOY_count']);
                    } else {
                        $('td', row).eq(2).html('-');
                    }

                    if (data['TC_count'] != null) {
                        $('td', row).eq(3).html(data['TC_count']);
                    } else {
                        $('td', row).eq(3).html('-');
                    }

                    if (data['NON_count'] != null) {
                        $('td', row).eq(4).html(data['NON_count']);
                    } else {
                        $('td', row).eq(4).html('-');
                    }
                    

                    $('td', row).eq(5).attr('class', 'style-blue-input text-right');
                    $('td', row).eq(5).attr('style', 'padding-right:5px!important;');
                    $('td', row).eq(5).html(data['credit_sum']==0?'':prettyValue(data['credit_sum']));

                    $('td', row).eq(6).attr('class', 'text-right');
                    $('td', row).eq(6).attr('style', 'padding-right:5px!important;')
                    $('td', row).eq(6).html(data['debit_sum']==0?'':prettyValue(data['debit_sum']));
                    //$('td', row).eq(6).attr('class', 'text-right right-border');
                    for (var i=1;i<16;i++)
                    {
                        if (i == 2) {
                            dest_obj = $('td', row).eq(7);
                        }
                        else if (i == 1) {
                            dest_obj = $('td', row).eq(8);
                        }
                        else if (i == 6) {
                            dest_obj = $('td', row).eq(9);
                        }
                        else if (i == 4) {
                            dest_obj = $('td', row).eq(10);
                        }
                        else if (i == 15) {
                            dest_obj = $('td', row).eq(11);
                        }
                        else if (i == 3) {
                            dest_obj = $('td', row).eq(12);
                        }
                        else if (i == 5) {
                            dest_obj = $('td', row).eq(13);
                        }
                        else if (i == 7) {
                            dest_obj = $('td', row).eq(14);
                        }
                        else if (i == 8) {
                            dest_obj = $('td', row).eq(15);
                        }
                        else if (i == 9) {
                            dest_obj = $('td', row).eq(16);
                        }
                        else if (i == 10) {
                            dest_obj = $('td', row).eq(17);
                        }
                        else if (i == 11) {
                            dest_obj = $('td', row).eq(18);
                        }
                        else if (i == 12) {
                            dest_obj = $('td', row).eq(19);
                        }
                        else {
                            dest_obj = null;
                        }

                        if (i == 15) {
                            //$(dest_obj).attr('class', 'text-right right-border');
                        }

                        if (data['debit_list'][i] != undefined)
                        {
                            if (i == 15) {
                                //$(dest_obj).attr('class', 'text-right right-border');
                            } else {
                                $(dest_obj).attr('class', 'text-right');
                            }
                            
                            if ((i==1) || (i==2) || (i==4)|| (i==6) || (i==15)) {
                                $(dest_obj).attr('style', 'padding-right:5px!important;color:#9c9c9c!important')
                            } else {
                                $(dest_obj).attr('style', 'padding-right:5px!important;')
                            }
                            
                            $(dest_obj).html(prettyValue(data['debit_list'][i]));
                        }
                        else {
                            if (dest_obj != null) $(dest_obj).html('');
                        }
                    }
                },
                drawCallback: function (response) {
                    if (response.json.data.length <= 0) return;
                    var tab = document.getElementById('table-income-expense-body');
                    var i,j;
                    for (i=0;i<15;i++) table_sums[i] = 0;
                    var time_average = 0;
                    for(var j=0; j<tab.rows.length; j++)
                    {
                        var value_str = tab.rows[j].childNodes[1].innerHTML;
                        if ((value_str != "") && (value_str != "-"))
                        {
                            time_average += parseFloat(value_str.replace(",",""));
                        }

                        for (var i=0;i<15;i++)
                        {
                            var value_str = tab.rows[j].childNodes[5+i].innerHTML;
                            if ((value_str != "") && (value_str != "-"))
                            {
                                table_sums[i] += parseFloat(value_str.replace(",",""));
                            }
                        }
                    }
                    
                    var report_html = "";
                    report_html = "<tr style='height:30px;'><td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer style-normal-header sub-small-header text-center disable-td'>年均</td>";
                    time_average = prettyValue(time_average / 3);
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='text-center table-footer sub-small-header disable-td'>" + time_average + "</td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer sub-small-header disable-td' colspan='3'></td>";
                    for(i=0;i<15;i++)
                    {
                        var value = table_sums[i] / 3;
                        if (i == 1)
                            //report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right right-border" + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right" + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                        else if (i > 1 && i < 7)
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header style-red-header text-right " + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                        else
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right " + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                    }
                    report_html += "</tr>";

                    report_html += "<tr style='height:30px;'><td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer style-normal-header sub-small-header text-center disable-td'>月均</td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='text-center table-footer sub-small-header disable-td'></td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer sub-small-header disable-td' colspan='3'></td>";
                    for(i=0;i<15;i++)
                    {
                        var value = table_sums[i] / 3 / 363 * 31;
                        if (i < 4) value = 0;
                        if (i == 1)
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right" + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                            //report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right right-border" + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                        else if (i > 1 && i < 7)
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header style-red-header text-right " + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                        else
                            report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-right " + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                    }
                    report_html += "</tr>";

                    report_html += "<tr style='height:30px;'><td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer style-normal-header sub-small-header text-center disable-td'>日均</td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='text-center table-footer sub-small-header disable-td'></td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='table-footer sub-small-header disable-td' colspan='3'></td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='text-center table-footer sub-small-header disable-td'></td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;' class='text-center table-footer sub-small-header disable-td'></td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header style-red-header text-right' style='padding:5px!important;'></td>";
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header style-red-header text-right' style='padding:5px!important;'></td>";

                    value = (table_sums[4] + table_sums[5] + table_sums[6]) / 363;
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header style-red-header text-center " + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;' colspan='3'>" + (value==0?'':'$'+prettyValue(value)) + "</td>";
                    value = (table_sums[7] + table_sums[8] + table_sums[9] + table_sums[10] + table_sums[11] + table_sums[12] + table_sums[13]+ table_sums[14]) / 363;
                    report_html += "<td style='box-shadow: inset 0 -1px #000, 1px -1px #000;padding:5px!important;' class='table-footer style-normal-header sub-small-header text-center " + (value>=0?'style-blue-input':'style-red-input') + "' style='padding:5px!important;' colspan='8'>" + (value==0?'': '$'+prettyValue(value)) + "</td>";
                    report_html += "</tr>";
                    $('#table-income-expense-body').append(report_html);

                    var inputs = $('input[name="input[]"]');
                    var outputs = $('input[name="output[]"]');
                    for (var i=0;i<8;i++) { inputs[i].value = ""; outputs[i].value = "";}
                    if (response.json.costs != null) {
                        inputs[0].value = response.json.costs['input1'];
                        inputs[1].value = response.json.costs['input2'];
                        inputs[2].value = response.json.costs['input3'];
                        inputs[3].value = response.json.costs['input4'];
                        inputs[4].value = response.json.costs['input5'];
                        inputs[5].value = response.json.costs['input6'];
                        inputs[6].value = response.json.costs['input7'];
                        inputs[7].value = response.json.costs['input8'];
                    }
                    setValues();
                }
            });

            $('.paginate_button').hide();
            $('.dataTables_length').hide();
            $('.paging_simple_numbers').hide();
            $('.dataTables_info').hide();
            $('.dataTables_processing').attr('style', 'position:absolute;display:none;visibility:hidden;');
        }

        function changeTableShip() {
            shipid_table = $('#select-table-ship').val();
            selectTableInfo();
        }
        
        $('#select-table-ship').on('change', function() {
            selectTableInfo();
        });

        function selectTableInfo()
        {
            shipid_table = $("#select-table-ship").val();
            $('#table_info').html('"' + $("#select-table-ship option:selected").attr('data-name') + '"');
            $('#costs_info').html('"' + $("#select-table-ship option:selected").attr('data-name') + '"');

            if (listTable == null) {
                initTable();
            }
            else
            {
                listTable.column(1).search(shipid_table, false, false).draw();
            }
        }

        function prettyValue(value)
        {
            return parseFloat(value).toFixed(2).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,");
        }

        const DAY_UNIT = 1000 * 3600;
        const COMMON_DECIMAL = 2;

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

        function alertAudio() {
            document.getElementById('warning-audio').play();
        }

        function fnExcelTableReport()
        {
            var tab_text="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            var real_tab = document.getElementById('table-income-expense-list');
            var tab = real_tab.cloneNode(true);
            tab_text=tab_text+"<tr><td colspan='20' style='font-size:20px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>" + $('#table_info').html() + "最新三年数据</td></tr>";
            for(var j = 0; j < tab.rows.length ; j++)
            {
                if (j == 0) {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        if (i == 7)
                            tab.rows[j].childNodes[i].style.width = '1500px';
                        else
                            tab.rows[j].childNodes[i].style.width = '100px';
                        tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                    }
                    tab.rows[j].childNodes[0].style.width = '80px';
                    tab.rows[j].childNodes[1].style.width = '80px';
                }
                else if (j == 1) {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                    }
                }
                else if (j >= (tab.rows.length - 3))
                {
                    for (var i=0; i<tab.rows[j].childElementCount;i++) {
                        tab.rows[j].childNodes[i].style.height = "30px";
                        tab.rows[j].childNodes[i].style.fontWeight = "bold";
                        tab.rows[j].childNodes[i].style.backgroundColor = '#ebf1de';
                    }
                }
                tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
            }
            tab_text=tab_text+"</table>";

            real_tab = document.getElementById('table-expect-cost');
            tab_text+="<table border='1px' style='text-align:center;vertical-align:middle;'>";
            tab = real_tab.cloneNode(true);
            tab_text+="<tr><td colspan='9' style='font-size:24px;font-weight:bold;border-left:hidden;border-top:hidden;border-right:hidden;text-align:center;vertical-align:middle;'>" + $('#table_info').html() + "日均成本</td></tr>";
            for(var j = 0; j < tab.rows.length ; j++)
            {
                if (j == 0) {
                    for (var i=1; i<tab.rows[j].childElementCount*2;i+=2) {
                        tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                    }
                }
                else if (j == 1) {
                    for (var i=1; i<tab.rows[j].childElementCount*2;i+=2) {
                        tab.rows[j].childNodes[i].style.backgroundColor = '#d9f8fb';
                    }
                }
                else if (j == (tab.rows.length - 1))
                {
                    for (var i=1; i<tab.rows[j].childElementCount*2;i+=2) {
                        tab.rows[j].childNodes[i].style.height = "30px";
                        tab.rows[j].childNodes[i].style.fontWeight = "bold";
                        tab.rows[j].childNodes[i].style.backgroundColor = '#ebf1de';
                    }
                }
                else
                {
                    for (var i=3;i<19;i+=2)
                    {
                        var info = real_tab.rows[j].childNodes[i].childNodes[0].value;
                        tab.rows[j].childNodes[i].innerHTML = info;
                    }
                }
                tab_text=tab_text+"<tr style='text-align:center;vertical-align:middle;font-size:16px;'>"+tab.rows[j].innerHTML+"</tr>";
            }
            tab_text=tab_text+"</table>";
            
            tab_text=tab_text.replace(/<A[^>]*>|<\/A>/g, "");
            tab_text=tab_text.replace(/<img[^>]*>/gi,"");
            tab_text=tab_text.replace(/<input[^>]*>|<\/input>/gi, "");

            var filename = $('#table_info').html() + '_日均成本';
            exportExcel(tab_text, filename, filename);
            
            return 0;
        }

    </script>

@endsection
