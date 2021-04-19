<style type="text/css">
    #PoPy {
        position: absolute;
        background: transparent;
        z-index: 20000;
        width: 73px;
        top: 83px;
        left: 237px;
    }
</style>
<div class="head_tool wrap">
    <div class="section clearfix">
        <p class="phones fl">
            <i class="ic ic_top_num"></i>免费服务热线<em>0371-55338888</em>
        </p>
        <div class="fr">
            <div class="tol_links">
                <a href="javascript:void(0);" rel="nofollow"><i class="ic ic_top_weixin"></i>微信公众号</a>
                <div class="mark_con">
                    <span class="ic_up"><i></i></span>
                    <img src="{{asset("/assets/home/images/weixin.jpg")}}" alt="本职工作网公众号">
                    <p>好工人公众号</p>
                </div>
            </div>
            <div class="tol_links tol_appdownload">
                <a href="javascript:void(0);" rel="nofollow">
                    <i class="ic ic_top_app"></i>APP下载
                </a>
                <div class="mark_con">
                    <span class="ic_up"><i></i></span>
                    <img src="{{asset("/assets/home/images/app-ewm.png")}}" alt="本职工作网APP">
                    <p>好工人APP</p>
                </div>
            </div>
            <div class="tol_links">
                <a href="javascript:void(0);" rel="nofollow">
                    物料下载
                </a>
            </div>
        </div>
    </div>
</div>
<div class="head wrap">
    <div class="section clearfix">
        <h1 class="logo">
            <a href="{{url("/home/index.html")}}" title="本职工作网">
                <img src="{{asset("/assets/home/images/logo.png")}}" alt="本职工作网打工网"/>
            </a>
        </h1>
        <div class="site_select">
            <div class="cur_site">
                <i class="ic ic_top_location"></i>
                <em id="cur_site">@if($city_info) {{$city_info['title']}} @else 定位中... @endif</em>
            </div>
        </div>
        <div id="PoPy" style="display: none;">
            <div class="_citys">
                <span title="关闭" id="cColse">×</span>
                <ul id="_citysheng" class="_citys0">
                    <li class="citySel">省份</li>
                    <li>城市</li>
                </ul>
                <div id="_citys0" class="_citys1">
                    @if($province_arr)
                        @foreach($province_arr as $pro)
                            <a data-id="{{$pro['id']}}">{{$pro['title']}}</a>
                        @endforeach
                    @endif
                </div>
                <div style="display:none" id="_citys1" class="_citys1"></div>
            </div>
        </div>
        <ul class="nav">
            <li>
                <a href="{{url("/home/index.html")}}" @if($menu == 'Index') class="cur" @endif>首页<i class="ic_up"></i></a>
            </li>
            <li>
                <a href="{{url("/home/recruit/index.html")}}" @if($menu == 'Recruit') class="cur" @endif>找工作<i class="ic_up"></i></a>
            </li>
            <li>
                <a href="{{url("/home/shop/index.html")}}" @if($menu == 'Shop') class="cur" @endif>找门店<i class="ic_up"></i></a>
            </li>
            <li>
                <a href="{{url("/home/join/index.html")}}" @if($menu == 'Join') class="cur" @endif>招商加盟<i class="ic_up"></i></a>
            </li>
            <li>
                <a href="{{url("/home/news/index.html")}}" @if($menu == 'News') class="cur" @endif>新闻资讯<i class="ic_up"></i></a>
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

{{--<script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.15&key=a9ef099582b8abffad85df7a65246f36&plugin=AMap.CitySearch"></script>--}}
<script src="{{asset("/assets/home/js/jquery.js")}}"></script>
<script type="text/javascript">
    // function showCityInfo() {
    //     //实例化城市查询类
    //     var citysearch = new AMap.CitySearch();
    //     //自动获取用户IP，返回当前城市
    //     citysearch.getLocalCity(function(status, result) {
    //         if (status === 'complete' && result.info === 'OK') {
    //             if (result && result.city && result.bounds) {
    //                 var cityinfo = result.city;
    //                 document.getElementById('cur_site').innerHTML = cityinfo;
    //             }
    //         } else {
    //             document.getElementById('info').innerHTML = result.info;
    //         }
    //     });
    // }
    //
    // showCityInfo();

    $(".cur_site").click(function () {
        $("#PoPy").css("display","block");
    });
    $("#cColse").click(function () {
        $("#PoPy").css("display","none");
    });
    $("#_citys0 a").click(function () {
        var province = $(this).data("id");
        $("#_citys0 a").removeClass();
        $(this).attr("class","AreaS");
        $.get('/home/get_city_list', {id:province}, function (data) {
            if(data.length > 0){
                var html = "";
                $.each(data, function (i, val) {
                    html += "<a data-id='"+val.id+"'>"+val.title+"</a>";
                });

                $("#_citys1").html(html);

                //处理城市列表的展示
                $("#_citysheng li:eq(0)").removeClass();
                $("#_citysheng li:eq(1)").attr("class","citySel");
                $("#_citys0").css("display","none");
                $("#_citys1").css("display","block");
            }
        });
    });
    $("#_citysheng li").click(function () {
        $("#_citysheng li").removeClass();
        $(this).attr("class","citySel");
        var i = $(this).index();
        if(i == 0){
            $("#_citys0").css("display","block");
            $("#_citys1").css("display","none");
        }else{
            $("#_citys0").css("display","none");
            $("#_citys1").css("display","block");
        }
    });
    //切换城市
    $("#_citys1").on('click', 'a', function () {
        var city_id = $(this).data("id");

        $.get("/home/changeCity",{city_id:city_id}, function (data) {
            if(data){
                $("#cur_site").html(data.title);
                $("#PoPy").css("display","none");
            }
        });
    })
</script>


