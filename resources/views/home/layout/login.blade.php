<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>本职工作网</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="keywords" content="电子厂招聘，普工招聘，工厂招聘，招工信息网，电子厂直招，普工直招，电子厂招聘信息">
    <meta name="description" content="本职工作网是一家招工信息网，专业为工友提供各类工厂招聘信息，包括电子厂招聘信息、普工招聘、小时工招聘、暑假工招聘、寒假工招聘等,招聘信息每天更新。">

    @include('home.public.css')
    <link rel="stylesheet" href="{{asset("/assets/home/css/common.css")}}">
    @yield('styles')
</head>
<body class="reg" style="zoom: 1;">
<div class="head wrap">
    <div class="section clearfix">
        <div class="logo">
            <a href="{{url("/home/index.html")}}" title="工立方">
                <img src="{{asset("/assets/home/images/logo.png")}}">
            </a>
        </div>
        <h1 class="reg_title">{{$title}}</h1>
        <div class="head_phone">免费服务热线<em>0371-55338888</em></div>
    </div>
</div>

@yield('content')

<div class="reg_foot ta_center">Copyright © 2016 本职工作网 All Rights Reserved 豫ICP备16088888号-2</div>
@include('home.public.js')

@yield('scripts')
</body>
</html>
