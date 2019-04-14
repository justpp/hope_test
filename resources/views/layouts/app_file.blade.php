<!doctype html>
<!--[if lt IE 9]>
<html lang="en" class="ie8">
<![endif]-->
<!--[if gt IE 8]>
<html lang="en">
<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="renderer" content="webkit"/>
    <meta charset="utf-8">
    <title>cabinet</title>
    <!-- IE 8 以下跳转至浏览器升级页 -->

    <!-- load css -->
    <link rel="stylesheet" href="{{asset('cabinet/css/base.css')}}"/>
    <link rel="stylesheet" href="{{asset('cabinet/css/common.css')}}"/>

    <link rel="stylesheet" href="{{asset('cabinet/css/skin/white.css')}}"/>

    <!-- IE8 fixed -->
    @yield('css')
    <!-- 语言包 -->
    <script src='{{asset('cabinet/js/lang/zh-cn.js')}}'></script>
    <!-- config -->
    <script>
        @php
            include_once public_path('cabinet/js/jsconfig.php');
        @endphp
    </script>
    <!-- 核心库类 -->
    <script src='{{asset('cabinet/js/src/core.js')}}'></script>

</head>
<body class="ibbody">
<div class="ibcontainer">
    <!-- Header -->
    <div class="header" id="header">
        <div class="wrap">

    </div>
    <!-- Header end -->
    <!-- load script end -->
    <div class="wrap" id="mainer">

        <script src='{{asset('cabinet/js/src/base.js')}}'></script>
        <!-- @Todo: 放到 mainer 加载之后 -->
        <script src='{{asset('cabinet/js/lib/artDialog/artDialog.min.js')}}'></script>
        <script src='{{asset('cabinet/js/src/common.js')}}'></script>
        <!-- Mainer -->
    @yield('contain')
    <!-- Mainer end -->
    </div>
    <!-- Footer -->
        <!-- 授权获取 -->
        <script type="text/javascript">
            Ibos.app.s('LICENCE_VER', 'Vol');
        </script>
    </div>
</div>
<script src='{{asset('cabinet/js/src/service.js?6Cs')}}'></script>
@yield('js')
</body>
</html>



