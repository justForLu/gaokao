@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-user" id="layuiadmin-form-user" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$username}}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>充值玉豆</label>
            <div class="layui-input-block">
                <input type="text" name="bean" lay-verify="required|number" placeholder="请输入充值玉豆" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('user.store')
            <input type="button" lay-submit lay-filter="admin-user-submit" id="admin-user-submit" value="确认">
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
        }).use(['index', 'form', 'upload'], function(){
            var $ = layui.$,
                form = layui.form;

        })
    </script>
@endsection
