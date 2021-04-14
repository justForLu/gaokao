<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>欢迎登录巴迪人力后台管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    @include('admin.public.css')
</head>
<body class="layui-layout-body">

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        @include('admin.public.header')

        @include('admin.public.sidebar')

        @include('admin.public.pagetab')

        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
@include('admin.public.js')
@yield('scripts')
</body>
</html>
