<div class="head_tool wrap">
    <div class="section clearfix">
        <p class="phones fl">
            <i class="ic ic_top_num"></i>免费服务热线<em>0371-55338888</em>
        </p>
        <div class="fr">
            <div class="tol_links">
                <a href="javascript:void(0);" rel="nofollow"><i class="ic ic_top_weixin"></i>微信号</a>
                <div class="mark_con">
                    <span class="ic_up"><i></i></span>
                    <img src="{{asset("/assets/home/images/weixin.jpg")}}" alt="中夏教育">
                    <p>中夏教育</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="head wrap">
    <div class="section clearfix">
        <h1 class="logo">
            <a href="{{url("/home/index.html")}}" title="中夏教育">
                <img src="{{asset("/assets/home/images/logo.png")}}" alt="中夏教育"/>
            </a>
        </h1>
        <div class="site_select">
            <div class="cur_site">

            </div>
        </div>
        <ul class="nav">
            <li>
                <a href="{{url("/home/index.html")}}" @if($menu == 'Index') class="cur" @endif>首页<i class="ic_up"></i></a>
            </li>
            <li>
                <a href="{{url("/home/school/index.html")}}" @if($menu == 'School') class="cur" @endif>查高校<i class="ic_up"></i></a>
            </li>
            <li>
                <a href="{{url("/home/score/index.html")}}" @if($menu == 'Score') class="cur" @endif>历年分数线<i class="ic_up"></i></a>
            </li>
            <li>
                <a href="{{url("/home/article/index.html")}}" @if($menu == 'Article') class="cur" @endif>专业解读<i class="ic_up"></i></a>
            </li>
            <li>
                <a href="{{url("/home/about/index.html")}}" @if($menu == 'About') class="cur" @endif>关于我们<i class="ic_up"></i></a>
            </li>
        </ul>
        @if(isset($userInfo) && $userInfo)
            <div class="head_user">
                <div class="users">
                    <img src="@if($userInfo->image) {{$userInfo->image}}  @else {{asset("/assets/home/images/default_user_img.png")}}  @endif" alt="" class="head_avt">{{$userInfo->nickname}}<i class="ic_down"></i>
                </div>
                <ul>
                    <li><a href="{{url("/home/user/info.html")}}" rel="nofollow">个人中心</a></li>
                    <li><a href="{{url("/home/user/portrait.html")}}" rel="nofollow">修改头像</a></li>
                    <li><a href="{{url("/home/logout")}}" rel="nofollow">退出</a></li>
                </ul>
            </div>
        @else
            <div class="head_login">
                <a class="btns hover" href="{{url("/home/login")}}" target="_blank" rel="nofollow">登录</a>
                <a class="btns" href="{{url("/home/register")}}" target="_blank" rel="nofollow">注册</a>
            </div>
        @endif
    </div>
</div>

