@extends('admin.layout.base')

@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    用户总数
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{{$data['user_count']}}</p>
                    <p>平台用户总数</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    今日用户
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{{$data['user_today']}}</p>
                    <p>今日注册用户总数</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    昨日用户
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{{$data['user_yesterday']}}</p>
                    <p>昨日注册用户总数</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    上月用户
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{{$data['user_last_month']}}</p>
                    <p>上月注册用户总数</p>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        layui.config({
            base: "{{asset('/assets/plugins/layuiadmin/')}}/" //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'carousel','echarts', 'sample', 'senior'], function(){
            var $ = layui.$,
                carousel = layui.carousel,
                echarts = layui.echarts;

        })
    </script>
@endsection
