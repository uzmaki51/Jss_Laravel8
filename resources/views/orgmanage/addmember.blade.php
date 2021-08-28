@extends('layout.header')
<?php
$isHolder = Session::get('IS_HOLDER');
?>

@section('styles')
    <!--link href="{{ cAsset('css/pretty.css') }}" rel="stylesheet"/>
    <link href="{{ cAsset('css/dycombo.css') }}" rel="stylesheet"/-->
    
    <!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/-->
@endsection

@section('scripts')
    <script>
        var HOLDER = '{!! STAFF_LEVEL_SHAREHOLDER !!}';
        var CAPTAIN = '{!! STAFF_LEVEL_CAPTAIN !!}';
        var IS_HOLDER = '{!! $userinfo['pos'] !!}';
    </script>
@endsection

@section('content')
    <div class="main-content">
        <style>
            .add-td-input,.add-td-select{
                border:unset!important;
                margin-left:10px!important;
                padding:0px!important;
                height:25px!important;
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
                    <h4><b>{{trans("orgManage.title.MemberInfo")}}</b>
                    </h4>
                </div>
            </div>
            <div class="row col-md-12" style="margin-bottom: 4px;">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <div class="btn-group f-right">
                        <a id="btnPrev" class="btn btn-sm btn-primary btn-add" style="width: 80px" href="/org/userInfoListView">
                            <i class=""></i>< {{trans("orgManage.captions.prevPage")}}
                        </a>
                        @if(isset($userid)>0)
                        <a id="btnDelete" class="btn btn-sm btn-danger" style="width: 80px" onclick="javascript:deleteMember('{{ $userid }}')">
                            <i class="icon-remove"></i>{{ trans('common.label.delete') }}
                        </a>
                        @endif
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
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table id="sample-table-1" class="table-bordered" style="margin-left:auto;margin-right:auto;">
                                        <tbody>
                                        @if ($errors->any())
                                            <div class="alert alert-danger alert-light alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="zmdi zmdi-close"></i></button>
                                                @foreach ($errors->all() as $error)
                                                    <strong><i class="zmdi zmdi-alert-triangle"></i> {{ $error }}</strong><br/>
                                                @endforeach
                                            </div>
                                        @endif
                                            <tr>
                                                <td class="add-td-label" width="20%;">{{trans("orgManage.captions.name")}}<span class="require">*</span>:</td>
                                                <td class="add-td-text">
                                                    <input type="text" class="form-control add-td-input" name="name" id="name" value="{{ isset($userinfo) ? $userinfo['realname'] : old('name') }}" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="add-td-label" >{{trans("orgManage.captions.loginID")}}<span class="require">*</span>:</td>
                                                <td class="add-td-text">
                                                    <input type="text" class="form-control add-td-input" name="account" id="account" {{ isset($userinfo) ? 'readonly' : '' }} value="{{ isset($userinfo) ? $userinfo['account'] : old('account') }}" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="add-td-label" colspan="1">{{trans("orgManage.captions.officePosition")}}<span class="require">*</span>:</td>
                                                <td class="add-td-text">
                                                    <select class="form-control add-td-select" id="pos" style="width: 98%; margin-left: 6px!important;" name="pos">
                                                        @foreach(g_enum('StaffLevelData') as $key => $item)
                                                            <option value="{{ $key }}" {{ (isset($userinfo) && ($userinfo['pos']==$key)) || old('pos') == $key ? 'selected' : '' }} >{{ $item[0] }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="add-td-label" colspan="1">{{trans("orgManage.captions.phoneNumber")}}:</td>
                                                <td class="add-td-text">
                                                    <div class="input-group">
                                                        <input type="tel" id="rantel" name="phone" class="form-control add-td-input" value="{{ isset($userinfo) ? trim($userinfo['phone']) : old('phone') }}">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table id="sample-table-1" class="table-bordered" style="margin-left:auto;margin-right:auto;">
                                        <tbody>
                                            <tr>
                                                <td class="add-td-label" width="20%;" colspan="1">{{trans("orgManage.captions.enterDate")}}:</td>
                                                <td class="add-td-text">
                                                    <input class="form-control date-picker add-td-input" style="text-align: left!important;" name="enterdate" type="text" data-date-format="yyyy-mm-dd" value="{{ isset($userinfo) ? $userinfo['entryDate'] : old('enterdate') }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="add-td-label" colspan="1">{{trans("orgManage.captions.missDate")}}:</td>
                                                <td class="add-td-text">
                                                    <div class="input-group">
                                                        <input class="form-control date-picker add-td-input" style="text-align: left!important;" name="releaseDate" type="text" data-date-format="yyyy-mm-dd" value="{{ isset($userinfo) ? $userinfo['releaseDate'] : old('releaseDate') }}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="add-td-label" colspan="1">{{trans("orgManage.captions.remark")}}:</td>
                                                <td class="add-td-text">
                                                    <input type="text" class="form-control add-td-input" name="remark" id="remark" value="{{ isset($userinfo) ? $userinfo['remark'] : old('remark') }}" required>
                                                </td>
                                            </tr>
                                            @if(isset($userinfo))
                                                <tr>
                                                    <td class="add-td-label" >{{trans("orgManage.captions.resetPass")}}:</td>
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
                        </div>
                        <div class="row" style="margin-top:20px;" id="menu-list">
                            <h4>职员权限</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
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
                                                    <input type="checkbox" onclick="check({{$index}})" id="{{'group'.$index}}" name="{{'group'.$index}}" @if ($cflag==true) checked="checked" @endif>
                                                    <input type="checkbox" id="{{$pmenu['id']}}" name="{{$pmenu['id']}}" style="display: none" @if ($cflag==true) checked="checked" @endif>
                                                </td>
                                        @else
                                            <tr id="{{'row'.$index}}">
                                                @if($pmenu['parentId']==0)
                                                    <td class="custom-td-label">
                                                        {{$pmenu['title']}}
                                                    </td>
                                                @endif
                                                <td class="custom-td-text" style="width: 3%; t ext-align: center">
                                                    <input type="checkbox" onclick="check({{$index}})" id="{{'group'.$index}}" checked="checked" name="{{'group'.$index}}">
                                                    <input type="checkbox" id="{{$pmenu['id']}}" name="{{$pmenu['id']}}" style="display: none" checked>
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
                                                                <input type="checkbox" class="{{'row'.$index}}" onclick="checkchild({{$index}}, this)" id="{{'row'.$menu['id']}}" name="{{'row'.$menu['id']}}" @if(($cflag==true) || ($flag1==true)) checked="checked" @endif>
                                                                <input type="checkbox" id="{{$menu['id']}}" name="{{$menu['id']}}" style="display: none" @if (($cflag==false) && ($flag1==true)) checked="checked" @endif>
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
                        <div class="row" id="ship-list">
                            <h4>SHIP SELECTION</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <td  colspan="3" style="text-align: left!important;">
                                                <?php $registerList = explode(',', $userinfo['shipList']);?>
                                                <select multiple="multiple" class="chosen-select form-control width-100" id="select-ship-list" name="shipList[]" data-placeholder="选择船舶...">
                                                    @foreach($shipList as $key => $item)
                                                        <option value="{{ $item['IMO_No'] }}" {{ in_array($item['IMO_No'], $registerList) ? 'selected' : '' }}>{{ $item['NickName'] }}</option>
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

            if(IS_HOLDER == HOLDER || IS_HOLDER == CAPTAIN)
            {
                var ship_list = $('#select-ship-list').val();
                if (ship_list == null) {
                    alert("Please select a ship!");
                    $('#select-ship-list').focus();
                    return;
                } else {
                    if (ship_list.length != 1) {
                        alert("It's allowed to select only one ship!");
                        $('#select-ship-list').focus();
                        return;
                    }
                }
            }
            
            submitted = true;
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
            submit();
        });

        $(function() {
            @if(isset($state))
            var state = '{!! $state !!}';
            if(state == 'success') {
                $.gritter.add({
                    title: '成功',
                    text: '职员信息已正确保存。',
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

            if(IS_HOLDER == HOLDER) {
                $('#menu-list').hide();
                $('#ship-list').show();
            } else if (IS_HOLDER == CAPTAIN) {
                $('#menu-list').show();
                $('#ship-list').show();
            } else {
                $('#menu-list').show();
                $('#ship-list').hide();
            }

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

        $('[name=pos]').on("change", function() {
            if($(this).val() == HOLDER) {
                $('#menu-list').hide();
                $('#ship-list').show();
            } else if($(this).val() == CAPTAIN) {
                $('#menu-list').show();
                $('#ship-list').show();
            } else {
                $('#menu-list').show();
                $('#ship-list').hide();
            }
        })
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
