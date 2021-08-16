<!DOCTYPE html>
<html lang="cn">
<head>
    <title>{{ env('APP_NAME') }}</title>
    <meta charset="utf-8"/>
    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- STYLES -->
    <link rel="stylesheet" href="{{ asset('/assets/css/theme.css') }}"/>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/bootstrap-overrides.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/font-awesome.min.css') }}"/>
    <link rel="icon" type="image/png" href="{{ cAsset('/assets/css/img/logo.png') }}" sizes="192x192">
    <link href="{{ asset('/assets/css/chosen.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/css/colorbox.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/css/ace.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/ace-rtl.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/ace-skins.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/jquery.gritter.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/css/base.css') }}" />
    <link href="{{ asset('/assets/css/datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/bootstrap-timepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/colorpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/jquery-ui-1.10.3.full.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/assets/css/jquery.treeview.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/common.css') }}" rel="stylesheet">
    @yield('styles')

    <!-- SCRIPTS -->
    <script src="{{ asset('/assets/js/ace-extra.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery-2.0.3.min.js') }}"></script>
    <script src="{{ asset('/assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('/assets/js/fuelux/fuelux.tree.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.treeview.js') }}"></script>
    <script src="{{ asset('/assets/js/bootbox.min.js') }}"></script>
    <script src="{{ asset('/assets/js/ace.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/js/typeahead-bs2.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery-ui-1.10.3.full.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.gritter.min.js')}}"></script>
    <script src="{{ asset('/assets/js/jquery.toast.min.js')}}"></script>
    <script src="{{ asset('/assets/js/jquery.slides.js')}}"></script>
    <script src="{{ asset('/assets/js/util.js')}}"></script>
</head>

<?php
    $routeName = Request::route()->getName();
    $menuList = Session::get('menusList');
    $id = Request::get('menuId');
?>

<body class="skin-1">
<header id="header">
    <div class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header" style="width:10%;">
                <a href="/home" class="navbar-brand">
                    <img class="navbar-img" src="{{ asset('/assets/avatars/logo.png') }}" alt=""/>
                </a>
            </div>
            
            <div id="menuToggle" class="sp-menu">
                <input type="checkbox" class="hamburger-input"/>

                <span></span>
                <span></span>
                <span></span>
                
                <!--
                Too bad the menu has to be inside of the button
                but hey, it's pure CSS magic.
                -->
                <ul class="nav nav-list" id="menu">
                    <li>
                        <a href="{{ route('home') }}">
                            首页
                        </a>
                    </li>
                    @foreach($menuList as $key => $item)
                        @if($item['parent'] == 0)
                            <li>
                                <a href="{{ (count($item['children']) == 0 ? '/' . $item['controller'] . '?menuId=' . $item['id'] : '#') }}" class="dropdown-toggle">{{ $item['title'] }}</a>
                                <ul class="submenu nav-hide">
                                    <li class="">
                                        @foreach($item['children'] as $key => $sub)
                                            <a href="{{ (count($sub['children']) == 0 ? '/' . $sub['controller'] . '?menuId=' . $sub['id'] : '#') }}" class="{{ count($sub['children']) == 0 ? '' : 'dropdown-toggle' }}">
                                                {{ $sub['title'] }}
                                            </a>
                                            @if(count($sub['children']) > 0)
                                                <ul class="submenu nav-hide">
                                                    @foreach($sub['children'] as $value)
                                                        <li class="">
                                                            <a href="/{{ $value['controller'] . '?menuId=' . $value['id'] }}">
                                                                {{ $value['title'] }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endforeach
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="sp-menu overlay-show" id="overlay-div" style="display: none;"></div>

            <div id="container">
                <nav>
                    <ul class="pc-menu">
                        @if(Auth::user()->pos != STAFF_LEVEL_SHAREHOLDER)
                            <li class="{{ $routeName == 'home' ? 'menu-active' : '' }} parent">
                                <a href="/">{{ trans('home.title.dashboard') }}</a>
                            </li>
                        @endif

                        @foreach($menuList as $key => $item)
                            @if($item['parent'] == 0)
                                <li class="{{ ($routeName != 'home' && in_array($id, $item['ids'])) ? 'menu-active' : '' }} parent">
                                    @if($item['controller'] == '')
                                        <a href="/{{ $item['children'][0]['controller'] . '?menuId=' . $item['id'] }}" class="link">{{ $item['title'] }}</a>
                                    @else
                                        <a href="/{{ $item['controller'] . '?menuId=' . $item['id'] }}" class="link">{{ $item['title'] }}</a>
                                    @endif
                                    <ul class="children">
                                        @foreach($item['children'] as $key => $sub)
                                            <li>
                                                <a href="/{{ $sub['controller'] == '' || $sub['controller'] == ' ' ? (count($sub['children']) > 0 ? $sub['children'][0]['controller'] : '') : $sub['controller']  }}{{ '?menuId=' . $sub['id'] }}">{{ $sub['title'] }}
                                                    @if(count($sub['children']) > 0)
                                                        <img class="has-child" src="{{ cAsset('assets/img/icons/right-arrow.png') }}">
                                                    @endif
                                                </a>
                                                <ul class="children third-level">
                                                    @foreach($sub['children'] as $value)
                                                        <li><a href="/{{ $value['controller'] . '?menuId=' . $value['id'] }}">{{ $value['title'] }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<script>
    var PUBLIC_URL = '{{ cAsset('/') . '/' }}';
    var BASE_URL = PUBLIC_URL;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

</script>

<div class="main-container {{ $routeName == 'home' || $routeName == 'home.index' ? '' : 'inner-wrap' }}" id="main-container">
    <div class="main-container-inner">
        @yield('content')
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="icon-double-angle-up icon-only bigger-110"></i>
    </a>
</div>
<button id="trigger-btn" type="button" class="d-none"></button>

<footer class="footer d-none">
    <p class="footer-title">
        <a href="/"><span class="blue bolder" style="line-height: 1;">JSS</span></a>&nbsp; &nbsp;船舶管理信息系统 ©  {{ (date('Y') - 1) . ' ~ ' . date('Y')  }}</span>&nbsp; &nbsp;
    </p>
</footer>
<audio controls="controls" class="d-none" id="warning-audio">
    <source src="{{ cAsset('assets/sound/delete.wav') }}">
    <embed src="{{ cAsset('assets/sound/delete.wav') }}" type="audio/wav">
</audio>
<audio controls="controls" class="d-none" id="alert-audio">
    <source allow="autoplay" src="{{ cAsset('assets/sound/alert.mp3') }}">
    <embed allow="autoplay" src="{{ cAsset('assets/sound/alert.mp3') }}" type="audio/mp3">
</audio>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>" + "<" + "/script>");
</script>
<script src="{{ asset('js/__common.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.inputlimiter.min.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('/assets/js/ship_process.js') }}"></script>
@yield('scripts')
</body>
</html>
<script>
    $(function() {
        $('#trigger-btn').click();
    });
</script>