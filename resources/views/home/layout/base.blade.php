<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>本职工作网</title>
    <meta name="keywords" content="高考报名，高校查询，大学查询，录取分数查询，分数线查询，内蒙古大学，中夏教育" />
    <meta name="description" content="内蒙古通辽市中夏教育，是一家专业的一对一教育机构，针对高三学生，提高考试成绩，帮助学生报考大学。" />

    @include('home.public.css')
    @yield('styles')
</head>
<body>

@include('home.public.header')

@yield('content')

@include('home.public.footer')

@include('home.public.js')
@yield('scripts')
</body>
</html>
