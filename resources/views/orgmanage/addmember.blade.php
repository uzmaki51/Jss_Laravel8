@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
?>

@section('styles')
    <!--link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/-->
    
    <!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/-->
@endsection

@section('content')
    <div class="main-content">
        <style>
            .add-td-input,.add-td-select{
                border:unset!important;
                margin-left:10px!important;
                padding:0px!important;
            }

            .add-td-label {
                font-size:14px!important;
                background-color:#d9f8fb !important;
                text-align: left!important;
                padding:8px 10px!important;
            }

            .add-td-text {
                background-color: #FFFFFF;
                font-weight: normal;
                vertical-align: middle;
            }
            .cost-item-odd {
                background-color: #f5f5f5;
            }

            .cost-item-even:hover {
                background-color: #ffe3e082;
            }

            .cost-item-odd:hover {
                background-color: #ffe3e082;
            }

        </style>
        <div class="page-content">
            <div class="page-header">
                <div class="col-md-3">
                    <h4><b>{{transOrgManage("title.MemberInfo")}}</b>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            @if(!isset($userid)){{transOrgManage("captions.add")}}@else {{transOrgManage("captions.change")}} @endif
                        </small>
                    </h4>
                </div>
            </div>
            <div class="row col-md-12" style="margin-bottom: 4px;">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <div class="btn-group f-right">
                        <a id="btnPrev" class="btn btn-sm btn-primary btn-add" style="width: 80px" onclick="javascript:goBackPage()">
                            <i class=""></i>< {{transOrgManage("captions.prevPage")}}
                        </a>
                        <a id="btnDelete" class="btn btn-sm btn-danger" style="width: 80px" onclick="javascript:deleteMember('{{ $userid }}')">
                            <i class="icon-remove"></i>{{ trans('common.label.delete') }}
                        </a>
                        <a id="btnSave" type="button" class="btn btn-sm btn-success" style="width: 80px">
                            <i class="icon-save"></i>{{ trans('common.label.save') }}
                        </a>
                    </div>
                </div>
            </div>
            
            @if(isset($userid)>0)
            <form id="validation-form" action="memberupdate" role="form" method="POST" enctype="multipart/form-data">
            @else
            <form id="validation-form" action="memberadder" role="form" method="POST" enctype="multipart/form-data">
            @endif
            <div style="margin-top:8px;">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="userid" id="userid" value="@if(isset($userid)){{$userid}} @endif">
                <div class="col-md-12">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table-bordered" style="margin-left:auto;margin-right:auto;">
                                <tbody>
                                <tr>
                                    <td class="add-td-label" width="10%;">{{transOrgManage("captions.name")}}<span class="require">*</span>:</td>
                                    <td class="add-td-text">
                                        <input type="text" class="form-control add-td-input" name="name" id="name" value="@if(isset($userinfo)){{$userinfo['realname']}}@endif" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" >{{transOrgManage("captions.loginID")}}<span class="require">*</span>:</td>
                                    <td class="add-td-text">
                                        <input type="text" class="form-control add-td-input" name="account" id="account" value="@if(isset($userinfo)){{$userinfo['account']}}@endif" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.officePosition")}}<span class="require">*</span>:</td>
                                    <td class="add-td-text">
                                        <select class="form-control add-td-select" id="pos" name="pos" style="margin-left: 9px;">
                                            <option value="-1" selected></option>
                                            @foreach($pos as $post)
                                                <option value="{{$post['id']}}" @if ((isset($userinfo))&&($userinfo['pos']==$post['id'])) selected @endif >{{$post['title']}}</option>
                                            @endforeach
                                            <option value="{{ IS_SHAREHOLDER }}" {{ $userinfo['pos'] == IS_SHAREHOLDER ? 'selected' : '' }}>{{ transOrgManage("captions.stockholder") }}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.phoneNumber")}}:</td>
                                    <td class="add-td-text">
                                        <div class="input-group">
                                            <input type="tel" id="rantel" name="phone" class="form-control add-td-input" value="@if(isset($userinfo)){{trim($userinfo['phone'])}}@endif">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.enterDate")}}:</td>
                                    <td class="add-td-text">
                                        <div class="input-group">
                                            <input class="form-control date-picker add-td-input" style="text-align: left!important;" name="enterdate" type="text" data-date-format="yyyy-mm-dd" value="@if(isset($userinfo)){{$userinfo['entryDate']}}@endif">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.missDate")}}:</td>
                                    <td class="add-td-text">
                                        <div class="input-group">
                                            <input class="form-control date-picker add-td-input" style="text-align: left!important;" name="releaseDate" type="text" data-date-format="yyyy-mm-dd" value="@if(isset($userinfo)){{$userinfo['releaseDate']}}@endif">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="add-td-label" colspan="1">{{transOrgManage("captions.remark")}}:</td>
                                    <td class="add-td-text">
                                        <input type="text" class="form-control add-td-input" name="remark" id="remark" value="@if(isset($userinfo)){{$userinfo['remark']}}@endif" required>
                                    </td>
                                </tr>
                                @if(isset($userinfo))
                                    <tr>
                                        <td class="add-td-label" >{{transOrgManage("captions.resetPass")}}:</td>
                                        <td class="add-td-text" style="">
                                            <div class="input-group">
                                                <input type="checkbox" class="form-control add-td-input" style="width: fit-content; margin-right: 10px; margin-left: 10px;margin-bottom:5px;" name="password_reset" id="password_reset">
                                                <span>* 使用密码初始化功能，可将该职员的密码改为 {{ DEFAULT_PASS }}。</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <h4>职员权限</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                <!--tr>
                                    <td class="custom-td-label" colspan="3"><h4>职员权限</h4></td>
                                </tr-->
                                <?php $index = 0; $cflag = true; ?>
                                @foreach($pmenus as $pmenu)
                                    @if(isset($userid))
                                        @if(in_array($pmenu['id'], explode(',', $userinfo['menu'])))
                                            <?php $cflag = true; ?>
                                        @else
                                            <?php $cflag = false; ?>
                                        @endif

                                        <tr id="{{'row'.$index}}">
                                            @if($pmenu['parentId'] == 0)
                                                <td class="custom-td-label">
                                                    {{$pmenu['title']}}
                                                </td>
                                            @endif
                                            <td class="custom-td-text" style="width: 3%; text-align: center">
                                                <input type="checkbox" onclick="check({{$index}})" id="{{'group'.$index}}" name="{{'group'.$index}}" @if ($cflag==true) checked="true" @endif>
                                                <input type="checkbox" id="{{$pmenu['id']}}" name="{{$pmenu['id']}}" style="display: none" @if ($cflag==true) checked="true" @endif>
                                            </td>
                                    @else
                                        <tr id="{{'row'.$index}}">
                                            @if($pmenu['parentId']==0)
                                                <td class="custom-td-label">
                                                    {{$pmenu['title']}}
                                                </td>
                                            @endif
                                            <td class="custom-td-text" style="width: 3%; t ext-align: center">
                                                <input type="checkbox" onclick="check({{$index}})" id="{{'group'.$index}}" name="{{'group'.$index}}">
                                                <input type="checkbox" id="{{$pmenu['id']}}" name="{{$pmenu['id']}}" style="display: none">
                                            </td>
                                    @endif
                                            <td class="custom-td-text" style="width: 77%">
                                                <div class="row">
                                                    @foreach($cmenus[$index] as $menu)
                                                        <?php $flag1 = false ?>
                                                        @if(isset($userid))
                                                            @if(in_array($menu['id'], explode(',',$userinfo['menu'])))
                                                                <?php $flag1 = true ?>
                                                            @endif
                                                        @endif
                                                        <div class="col-md-2">&nbsp
                                                            <input type="checkbox" class="{{'row'.$index}}" onclick="checkchild({{$index}}, this)" id="{{'row'.$menu['id']}}" name="{{'row'.$menu['id']}}" @if(($cflag==true) || ($flag1==true)) checked="true" @endif>
                                                            <input type="checkbox" id="{{$menu['id']}}" name="{{$menu['id']}}" style="display: none" @if (($cflag==false) && ($flag1==true)) checked="true" @endif>
                                                            <label>&nbsp{{$menu['title']}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $index++?>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <h4>选船(*只有持股者才能显示)</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td  colspan="3" style="text-align: left!important;">
                                            <?php $registerList = explode(',', $userinfo['attributes']['shipList']);?>
                                            <select multiple="multiple" class="chosen-select form-control width-100" name="shipList[]" data-placeholder="选择船舶...">
                                                @foreach($shipList as $key => $item)
                                                    <option value="{{ $item['attributes']['shipID'] }}" {{ in_array($item['attributes']['shipID'], $registerList) ? 'selected' : '' }}>{{ $item['attributes']['shipName_En'] }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <div id="modal-pos-list" class="modal modal-draggable" aria-hidden="true" style="display: none; margin-top: 15%;">
                <div class="dynamic-modal-dialog">
                    <div class="dynamic-modal-content" style="border: 0;">
                        <div class="dynamic-modal-header" data-target="#modal-step-contents">
                            <div class="table-header">
                                <button type="button"  style="margin-top: 8px; margin-right: 12px;" class="close" data-dismiss="modal" aria-hidden="true">
                                    <span class="white">&times;</span>
                                </button>
                                <h4 style="padding-top:10px;font-style:italic;">Position List</h4>
                            </div>
                        </div>
                        <div id="modal-pos-content" class="dynamic-modal-body step-content">
                            <div class="row" style="">
                                <div class="head-fix-div col-md-12" style="height:300px;">
                                    <table class="table-bordered pos-table">
                                        <thead>
                                        <tr style="background-color: #d9f8fb;height:18px;">
                                            <th class="text-center sub-header style-bold-italic" style="background-color: #d9f8fb;width:10%">OrderNo</th>
                                            <th class="text-center sub-header style-bold-italic" style="background-color: #d9f8fb;width:80%">Name</th>
                                            <th class="text-center sub-header style-bold-italic" style="background-color: #d9f8fb;"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="pos-table">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="btn-group f-right mt-20 d-flex">
                                        <button type="button" class="btn btn-success small-btn ml-0" onclick="javascript:dynamicPosSubmit('pos')">
                                            <img src="{{ cAsset('assets/images/send_report.png') }}" class="report-label-img">OK
                                        </button>
                                        <div class="between-1"></div>
                                        <a class="btn btn-danger small-btn close-modal" data-dismiss="modal"><i class="icon-remove"></i>Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <audio controls="controls" class="d-none" id="warning-audio">
            <source src="{{ cAsset('assets/sound/delete.wav') }}">
            <embed src="{{ cAsset('assets/sound/delete.wav') }}" type="audio/wav">
        </audio>
    </div>
    <script>
        var menuId = 10;
        function submit() {
            if ($('#name').val() == '') {
                $('#name').focus();
                return;
            }
            
            if ($('#account').val() == '') {
                $('#account').focus();
                return;
            }

            if ($('#pos').val() <= 0) {
                alert("Please select position!");
                return;
            }
            
            $('#validation-form').submit();
        }

        function alertAudio() {
            document.getElementById('warning-audio').play();
        }

        function deleteMember(userid) {
            alertAudio();
            bootbox.confirm("Are you sure you want to delete?", function (result) {
                if (result) {
                    $.ajax({
                        url: BASE_URL + 'org/memberInfo/delete',
                        type: 'post',
                        data: {
                            userid: userid,
                        },
                        success: function(result, status, xhr) {
                            console.log(result);
                            if(result == 1) {
                                location.href = BASE_URL + "org/userInfoListView?menuId=" + menuId;
                            } else {

                            }
                        }
                    })
                }
            });
        }
        function goBackPage() {
            location.href="userInfoListView";
        }
    </script>

    <script type="text/javascript">
        var token = '{!! csrf_token() !!}';
        var submitted = false;
        $("#btnSave").on('click', function() {
            submitted = true;
            submit();
        });

        $(function() {
            @if(isset($state))
            var state = '{!! $state !!}';
            if(state == 'success') {
                $.gritter.add({
                    title: '成功',
                    text: '员工信息已正确保存。',
                    class_name: 'gritter-success'
                });
            } else {
                $.gritter.add({
                    title: '错误',
                    text: state,
                    class_name: 'gritter-error'
                });
            }
            @endif
        });

        $('body').on('click', function(e) {
            var current = null;
            if ($(event.target).attr('class') == 'form-control dynamic-select-span' || $(event.target).attr('class') == 'dynamic-select__trigger') {
                current = $(event.target).closest('.dynamic-select-wrapper');
            }
            for (const selector of document.querySelectorAll(".dynamic-select-wrapper")) {
                if (current == null || selector != current[0])
                    selector.firstElementChild.classList.remove('open');
            }
        });

        function check(id) {
            var allcheck = document.getElementById('group' + id);
            var checks = document.getElementsByClassName('row' + id);

            for (var i = 0; i < checks.length; i++) {
                if (allcheck.checked == true) {
                    allcheck.nextElementSibling.checked = true;
                    checks[i].checked = true;
                } else {
                    allcheck.nextElementSibling.checked = false;
                    checks[i].checked = false;
                }
                checks[i].nextElementSibling.checked = false;
            }
        }

        function checkchild(id, checkObj) {
            var allcheck = document.getElementById('group' + id);
            var checks = document.getElementsByClassName('row' + id);
            checkObj.nextElementSibling.checked = checkObj.checked;

            var flag = true;
            for (var i = 0; i < checks.length; i++) {
                if (checks[i].checked == true) {
                    continue;
                } else {
                    flag = false;
                    break;
                }
            }
            if (flag == true) {
                allcheck.checked = true;
                allcheck.nextElementSibling.checked = true;
                for (var i = 0; i < checks.length; i++)
                    checks[i].nextElementSibling.checked = false;
            } else {
                allcheck.checked = false;
                allcheck.nextElementSibling.checked = false;
                for (var i = 0; i < checks.length; i++)
                    checks[i].nextElementSibling.checked = checks[i].checked;
            }
        }

        var $form = $('form');
        var origForm = $form.serialize();
        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = 'It looks like you have been editing something. '
                                    + 'If you leave before saving, your changes will be lost.';
            var newForm = $form.serialize();
            if ((newForm !== origForm) && !submitted) {
                (e || window.event).returnValue = confirmationMessage;
            }
            return confirmationMessage;
        });
    </script>

    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('/assets/js/dycombo.js') }}"></script>
@stop
