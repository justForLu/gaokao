<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>本职工作网</title>
    <meta name="keywords" content="电子厂招聘，普工招聘，工厂招聘，招工信息网，电子厂直招，普工直招，电子厂招聘信息" />
    <meta name="description" content="本职工作网是一家招工信息网，专业为工友提供各类工厂招聘信息，包括电子厂招聘信息、普工招聘、小时工招聘、暑假工招聘、寒假工招聘等，招聘信息每天更新。" />

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