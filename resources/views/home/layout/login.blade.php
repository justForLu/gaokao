<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>中夏教育</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="keywords" content="高考报名，高校查询，大学查询，录取分数查询，分数线查询，内蒙古大学，中夏教育" />
    <meta name="description" content="内蒙古通辽市中夏教育，是一家专业的一对一教育机构，针对高三学生，提高考试成绩，帮助学生报考大学。" />

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

<div class="reg_foot ta_center">Copyright © 2016 中夏教育 All Rights Reserved 蒙ICP备16088888号-2</div>
@include('home.public.js')

@yield('scripts')
</body>
</html>
