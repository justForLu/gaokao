@extends('home.layout.login')

@section('styles')
    <style type="text/css">
        .reg_lcont tr td{
            padding: 15px 7px;
        }
    </style>
@endsection

@section('content')
    <div class="w1200 of_hide reg_main">
        <div class="left reg_left">
            <div class="reg_lhead font_18" id="login_meth">
                <a id="login-btn1" class="cur">账号密码登录</a>
                <a id="login-btn2" class="">手机验证码登录</a>
            </div>
            <div id="login_item1" class="login_item show">
                <form class="J_ajaxForm" method="post" action="{{url("home/login")}}">
                    {{csrf_field()}}
                    <table class="reg_lcont font_16" cellspacing="20">
                        <tbody>
                        <tr>
                            <td class="reg_label">用户名</td>
                            <td>
                                <input type="text" name="username" class="reg_input3">
                            </td>
                            <td class="reg_tip">请输入您的手机号</td>
                        </tr>
                        <tr>
                            <td class="reg_label">密码</td>
                            <td>
                                <input type="password" name="password" class="reg_input3">
                            </td>
                            <td class="reg_tip">请输入密码</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="grey">
                                <a href="" class="right grey">忘记密码？</a>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="grey">
                                <button type="submit" class="reg_btn mt_30 J_ajax_submit_btn">登录</button>
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div id="login_item2" class="login_item">
                <form role="form" action="" method="post" class="J_ajaxForm" id="form2">
                    <table class="reg_lcont font_16" cellspacing="20">
                        <tbody>
                        <tr>
                            <td class="reg_label">手机号</td>
                            <td>
                                <input type="text" name="mobile" id="inputMobile" class="reg_input" placeholder="输入手机号">
                            </td>
                            <td class="reg_tip">请输入您的手机号</td>
                        </tr>
                        <tr>
                            <td class="reg_label">短信验证码</td>
                            <td>
                                <input type="text" name="code" class="left reg_input2" placeholder="输入短信验证码">
                                <span class="left reg_getCode send-sms" data-ajax="/Sms/loginSend.html">获取验证码</span>
                            </td>
                            <td class="reg_tip">点击获取短信验证码</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="grey">
                                <a href="" class="right grey">忘记密码？</a>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="grey">
                                <a href="javascript:void(0)" onclick="login(2)" class="reg_btn mt_30">登录</a>
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

        </div>

        <div class="right reg_right ta_center">
            <p class="mt_50 orange bold font_18">下载本职工作网APP</p>
            <p>手机报名 入职速度快50%</p>
            <p class="mt_20">
                <img src="{{asset("/assets/home/images/app-ewm.png")}}" width="122" height="122"></p>
            <p>扫一扫即刻体验</p>
            <div class="reg_rfoot grey">
                没有账号？<a href="{{url("/home/register")}}" class="orange">立即注册</a>
                <p class="mt_20">还可以使用以下方式登录</p>
                <p class="reg_www mt_20">
                    <a href="">
                        <img src="{{asset('/assets/home/images/weixin.png')}}" style="border: none;">
                    </a>
                    <a href="">
                        <img src="{{asset('/assets/home/images/qq.png')}}" style="border: none;">
                    </a>
                    <a href="">
                        <img src="{{asset('/assets/home/images/weibo.png')}}" style="border: none;">
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("#login-btn1").click(function () {
            $("#login_item1").attr("class","login_item show");
            $("#login_item2").attr("class","login_item");
            $("#login-btn1").attr("class","cur");
            $("#login-btn2").attr("class","");
        });
        $("#login-btn2").click(function () {
            $("#login_item1").attr("class","login_item");
            $("#login_item2").attr("class","login_item show");
            $("#login-btn1").attr("class","");
            $("#login-btn2").attr("class","cur");
        });
    </script>
@endsection

