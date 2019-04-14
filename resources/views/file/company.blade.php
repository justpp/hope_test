@extends('layouts.app_file')

@section('css')
    <link rel="stylesheet" href="{{asset('/cabinet/css/file_cabinets.css')}}">
@stop

<!-- Mainer -->

@section('contain')
    <div class="fmc">
        <div class="page-list">
            <div class="page-list-header">
                <div class="fc-dyna-bar">
				<span class="fc-dyna-tt">
					<i class="o-ol-trumpet"></i>
					<strong>最新动态：</strong>
				</span>
                    <div class="btn-group pull-right">
                        <button class="btn btn-small" id="prev_dyna_btn">
                            <i class="o-ol-chevron-up"></i>
                        </button>
                        <button class="btn btn-small" id="next_dyna_btn">
                            <i class="o-ol-chevron-down"></i>
                        </button>
                    </div>
                    <ul class="fc-dyna-list"></ul>
                </div>
            </div>
            <div class="page-list-mainer">
                <!-- 状态栏 -->
                <div class="fc-topbar clearfix">
                    <div class="fc-nav clearfix" id="fc_breadcrumb">

                    </div>
                    <div class="fc-filterbar">
                        <select name="order" id="fc_order" class="hide">
                            <option value="0">从新到旧</option>
                            <option value="1">从旧到新</option>
                            <option value="2">从小到大</option>
                            <option value="3">从大到小</option>
                            <option value="4">从A-Z排序</option>
                            <option value="5">从Z-A排序</option>
                        </select>
<!--                        <select name="type" id="fc_filter" class="hide">-->
<!--                            <option value="all">--><?php //echo $lang['Type all']; ?><!--</option>-->
<!--                            <option value="mark">--><?php //echo $lang['Type mark']; ?><!--</option>-->
<!--                            <option value="word">--><?php //echo $lang['Type word']; ?><!--</option>-->
<!--                            <option value="excel">--><?php //echo $lang['Type excel']; ?><!--</option>-->
<!--                            <option value="ppt">--><?php //echo $lang['Type ppt']; ?><!--</option>-->
<!--                            <option value="text">--><?php //echo $lang['Type text']; ?><!--</option>-->
<!--                            <option value="image">--><?php //echo $lang['Type image']; ?><!--</option>-->
<!--                            <option value="package">--><?php //echo $lang['Type package']; ?><!--</option>-->
<!--                            <option value="audio">--><?php //echo $lang['Type audio']; ?><!--</option>-->
<!--                            <option value="video">--><?php //echo $lang['Type video']; ?><!--</option>-->
<!--                        </select>-->
                    </div>
                </div>
                <!-- 文件列表 -->
                <div class="fc-list-cell">
                    <ul class="list-thumb clearfix" id="fc_list"></ul>
                </div>
            </div>
        </div>
    </div>
    @php
        include_once base_path('resources/views/file/template.php');
    @endphp
@stop

@section('js')
    <script src='{{asset('cabinet/js/cabinet_disk.js')}}'></script>
    <script src='{{asset('cabinet/js/lib/webuploader/webuploader.js')}}'></script>
    <script src='{{asset('cabinet/js/lib/webuploader/handlers.js')}}'></script>
    <script src='{{asset('cabinet/js/lib/underscore/underscore.js')}}'></script>
    <script src='{{asset('cabinet/js/lib/backbone/backbone.js')}}'></script>
    <script src='{{asset('cabinet/js/lib/jquery.pagination.js')}}'></script>
    <script src='{{asset('cabinet/js/app/ibos.pSelect.js')}}'></script>

    <script src='{{asset('cabinet/js/lang/zh-cn.js')}}'></script>
    <script src='{{asset('cabinet/js/cabinet.js')}}'></script>

    <script src='{{asset('cabinet/js/cabinet_company.js')}}'></script>

<script>
    Ibos.app.s({
        "pid": 1,
        "cabinetType": "company",
        "isAdministrator": 1
    })
    </script>
@stop

