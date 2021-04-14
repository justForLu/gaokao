@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">修改资料</div>
        <div class="layui-card-body">
            <div class="layui-form" style="padding: 20px 30px 0 0;">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="id" value="{{$userInfo->id}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">登录名</label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{$userInfo->username}}" autocomplete="off" class="layui-input" disabled style="border: none;">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="mobile" lay-verify="required|phone" value="{{$userInfo->mobile}}" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">微信号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="wechat" placeholder="请输入微信号" value="{{$userInfo->wechat}}" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline">
                        <input type="text" value="{{$userInfo->status_name}}" autocomplete="off" class="layui-input" disabled style="border: none;">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="admin-update-my-info">确定</button>
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
            form = layui.form ;

        form.on('submit(admin-update-my-info)', function (obj) {
            var data = obj.field;
            $.ajax({
                url:'/admin/manager/my_info',
                method: 'post',
                data: data,
                success: function (res) {
                    if(res.code === 0){
                        var msg = res.msg ? res.msg : '修改成功';
                        layer.msg(msg, function(){
                            location.href = '/admin/manager/my_info';
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
