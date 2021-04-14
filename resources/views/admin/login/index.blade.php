@extends('admin.layout.login')

@section('content')
    <div class="login-main">
        <div class="login-top">
            <span>巴迪人力后台管理系统</span>
            <span class="bg1"></span>
            <span class="bg2"></span>
        </div>
        <div class="layui-form login-bottom">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="center">
                <div class="item">
                    <span class="icon icon-2"></span>
                    <input type="text" name="username" lay-verify="required" value="admin" placeholder="用户名" class="layui-input">
                </div>
                <div class="item">
                    <span class="icon icon-3"></span>
                    <input type="password" name="password" lay-verify="required" value="123456" placeholder="密码" class="layui-input">
                    <span class="bind-password icon icon-4"></span>
                </div>
            </div>
            <div class="layui-form-item" style="text-align:center; width:100%;height:100%;margin:0px;">
                <button class="login-btn" lay-submit lay-filter="admin-user-login-submit" id="admin-user-login-submit">立即登录</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        layui.config({
            base: "{{asset('/assets/plugins/layuiadmin/')}}/" //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'form'], function(){
            var $ = layui.$,
                form = layui.form ;

            form.on('submit(admin-user-login-submit)', function (obj) {
                login();
            });
            function login() {
                var _token = $("input[name='_token']").val();
                var username = $("input[name='username']").val();
                var password = $("input[name='password']").val();
                $.ajax({
                    url: "{{url('admin/login')}}", //实际使用请改成服务端真实接口
                    method: 'post',
                    data: {_token:_token,username:username,password:password},
                    success: function(res){
                        //登入成功的提示与跳转
                        if(res.code === 0){
                            layer.msg('登录成功', {}, function(){
                                location.href = res.referrer; //后台主页
                            });
                            if (window.frames.length != parent.frames.length) {
                                window.parent.location.reload();;
                            }
                        }
                        var msg = res.msg ? res.msg : '登录失败';
                        layer.msg(msg);
                    }
                })
            }
        })
    </script>
@endsection

