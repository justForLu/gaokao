@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-manager" id="layuiadmin-form-manager" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>登录名</label>
            <div class="layui-input-inline">
                <input type="text" name="username" lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="mobile" lay-verify="required|phone" placeholder="请输入手机号" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>微信号</label>
            <div class="layui-input-inline">
                <input type="text" name="wechat" placeholder="请输入微信号" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色</label>
            <div class="layui-input-inline">
                <select name="role_id">
                    @if($roleList)
                        <option value="">请选择角色</option>
                        @foreach($roleList as $val)
                            <option value="{{$val->id}}">{{$val->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                {{\App\Enums\BasicEnum::enumSelect(\App\Enums\BasicEnum::ACTIVE,false,'status')}}
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('manager.store')
            <input type="button" lay-submit lay-filter="admin-manager-submit" id="admin-manager-submit" value="确认">
            @endcan
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
    })
</script>
@endsection
