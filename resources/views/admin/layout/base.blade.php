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
<body>

<div class="layui-fluid">
    @yield('content')
</div>

<script src="{{asset("/assets/plugins/layuiadmin/layui/layui.js")}}"></script>
@yield('scripts')
</body>
</html>
