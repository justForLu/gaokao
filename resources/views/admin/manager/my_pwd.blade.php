@extends('admin.layout.base')

@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">修改密码</div>
                <div class="layui-card-body" pad15>
                    <div class="layui-form" lay-filter="">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="layui-form-item">
                            <label class="layui-form-label">当前密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="oldPassword" lay-verify="required" lay-verType="tips" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">新密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="password" lay-verify="pass" lay-verType="tips" autocomplete="off" id="LAY_password" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">确认新密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="repassword" lay-verify="repass" lay-verType="tips" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="admin-update-my-pwd">确定</button>
                            </div>
                        </div>
                    </div>
                </div>
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
            form = layui.form;

        form.on('submit(admin-update-my-pwd)', function (obj) {
            var data = obj.field;
            $.ajax({
                url:'/admin/manager/my_pwd',
                method: 'post',
                data: data,
                success: function (res) {
                    if(res.code === 0){
                        var msg = res.msg ? res.msg : '修改成功';
                        layer.msg(msg, function(){
                            location.href = '/admin/manager/my_info'; //后台主页
                        });
                    }else{
                        var msg = res.msg ? res.msg : '修改失败';
                        layer.msg(msg);
                    }
                }
            })
        })
    })
</script>
@endsection
