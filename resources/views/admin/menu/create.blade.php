@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-menu" id="layuiadmin-form-menu" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="layui-form-item">
            <label class="layui-form-label">上级菜单</label>
            <div class="layui-input-inline">
                <select name="parent">
                    <option value="0">顶级菜单</option>
                    @if($list)
                        @foreach($list as $menuLevel1)
                            <option value="{{$menuLevel1->id}}">{{$menuLevel1->name}}</option>
                            @if($menuLevel1->children)
                                @foreach($menuLevel1->children as $menuLevel2)
                                    <option value="{{$menuLevel2->id}}">&nbsp;&nbsp;&nbsp;{{$menuLevel2->name}}</option>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>菜单名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>菜单地址</label>
            <div class="layui-input-inline">
                <input type="text" name="url" lay-verify="required" placeholder="请输入菜单地址" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>菜单编码</label>
            <div class="layui-input-inline">
                <input type="text" name="code" placeholder="请输入菜单编码" value="" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">菜单排序</label>
            <div class="layui-input-inline">
                <input type="text" name="sort" placeholder="请输入菜单排序" value="0" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                {{\App\Enums\BasicEnum::enumSelect(\App\Enums\BasicEnum::ACTIVE,false,'status')}}
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('menu.store')
            <input type="button" lay-submit lay-filter="admin-menu-submit" id="admin-menu-submit" value="确认">
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
