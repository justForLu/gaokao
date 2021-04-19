@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/user.css")}}">
@endsection

@section('content')
    <div class="user_top wrap">
        <div class="section">
            <div class="user_top_avt">
                <div class="imgs">
                    <img src="@if($userInfo->image) {{$userInfo->image}}  @else {{asset("/assets/home/images/default_user_img.png")}}  @endif" alt="">
                    <a href="" class="to_avt">
                        <i class="ic ic_avt_ico"></i>
                    </a>
                </div>
                <p>欢迎你，亲爱的 <em>{{$userInfo->nickname}}</em>！</p>
            </div>
            <div class="user_top_num">
                <a href="{{url("/home/user/message.html")}}" class="nums">
                    消息<em>0</em>
                </a>
                <a href="{{url("/home/user/collect_recruit.html")}}" class="nums">
                    我的收藏<em>{{$count_collect}}</em>
                </a>
            </div>
        </div>
    </div>

    <div class="user_main">
        <div class="section">
            <div class="s_l">
                <div class="user_left c_border">
                    <ul class="user_left_nav">
                        <li @if($menu == 'message') class="active" @endif><a href="{{url("/home/user/message.html")}}"><i class="ic iconfont">&#xe652;</i>消息中心</a></li>
                        <li @if($menu == 'info') class="active" @endif><a href="{{url("/home/user/info.html")}}"><i class="ic iconfont">&#xe650;</i>我的资料</a></li>
{{--                        <li @if($menu == 'cash_out') class="active" @endif><a href="{{url("/home/user/cash_out.html")}}"><i class="ic iconfont">&#xe669;</i>提现资料</a></li>--}}
                        <li @if($menu == 'collect') class="active" @endif><a href="{{url("/home/user/collect_recruit.html")}}"><i class="ic iconfont">&#xe674;</i>我的收藏</a></li>
                        <li @if($menu == 'portrait') class="active" @endif><a href="{{url("/home/user/portrait.html")}}"><i class="ic iconfont">&#xe651;</i>头像设置</a></li>
                        <li @if($menu == 'account') class="active" @endif><a href="{{url("/home/user/account.html")}}"><i class="ic iconfont">&#xe63c;</i>帐号管理</a></li>
                    </ul>
                    <div class="user_lapp">
                        <img src="{{asset("/assets/home/images/weixin.jpg")}}" alt="">
                        扫描二维码，即可订阅岗位，<br>数万岗位随手可得
                    </div>
                </div>
            </div>

            <div class="s_r">
                <div class="uc_title">
                    <i class="line"></i>
                    <h3>账号管理</h3>
                    <span class="more more_tab">
                        <a id="edit-pwd" class="active first">修改密码</a>
                        <a id="edit-mobile">修改绑定手机</a>
                    </span>
                </div>
                <div class="c_border form-pwd">
                    <form action="{{url("/home/user/sub_account_pwd")}}" method="post" id="form" class="form form-horizontal uc_form J_ajaxForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-item">
                            <label class="form-label" for="">当前密码：</label>
                            <div class="form-field">
                                <input type="password" placeholder="输入原密码" class="form-text" name="old_password">
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label" for="">新密码：</label>
                            <div class="form-field">
                                <input type="password" placeholder="输入新密码" class="form-text" name="password">
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label" for="">确认密码：</label>
                            <div class="form-field">
                                <input type="password" placeholder="再次输入新密码" class="form-text" name="password_confirmation">
                            </div>
                        </div>
                        <div class="form-action  action-left">
                            <button type="submit" class="btn btn_b btn_orange btn_round J_ajax_submit_btn">确认</button>
                        </div>
                    </form>
                </div>
                <div class="c_border form-mobile" style="display: none;">
                    <form action=""  id="form" class="form form-horizontal uc_form J_ajaxForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-item">
                            <label class="form-label" for="">新手机号：</label>
                            <div class="form-field">
                                <input type="text" placeholder="请输入新手机号" class="form-text" name="mobile">
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label" for="">验证码：</label>
                            <div class="form-field">
                                <input type="text" placeholder="请输入验证码" class="form-text" name="code">
                                <a href="javascript:void(0);" class="got_mark" id="getSms">获取验证码</a>
                            </div>
                        </div>
                        <div class="form-action  action-left">
                            <button type="submit" class="btn btn_b btn_orange btn_round J_ajax_submit_btn">确认</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset("/assets/home/js/user.js")}}"></script>
    <script type="text/javascript">
        $("#edit-pwd").click(function () {
            $(this).attr("class","active first");
            $(".form-pwd").css("display","block");
            $("#edit-mobile").attr("class","");
            $(".form-mobile").css("display","none");
        });
        $("#edit-mobile").click(function () {
            $(this).attr("class","active");
            $(".form-mobile").css("display","block");
            $("#edit-pwd").attr("class","first");
            $(".form-pwd").css("display","none");
        });
    </script>
@endsection


