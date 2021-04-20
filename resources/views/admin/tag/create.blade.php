@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-tag" id="layuiadmin-form-tag" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>标签名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" placeholder="请输入标签名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>标签简称</label>
            <div class="layui-input-block">
                <input type="text" name="shorter" lay-verify="required" placeholder="请输入标签简称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input type="text" name="sort" placeholder="请输入排序" value="0" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">排序越大越靠前</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                {{\App\Enums\BasicEnum::enumRadio(\App\Enums\BasicEnum::ACTIVE,'status')}}
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('tag.store')
            <input type="button" lay-submit lay-filter="admin-tag-submit" id="admin-tag-submit" value="确认">
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
                form = layui.form;
        })
    </script>
@endsection
