@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-permission" id="layuiadmin-form-permission" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>权限名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" placeholder="请输入用户名" value="{{$data->name}}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>权限编码</label>
            <div class="layui-input-inline">
                <input type="text" name="code" lay-verify="required" placeholder="请输入密码" value="{{$data->code}}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>所属菜单</label>
            <div class="layui-input-inline">
                <select name="role_id">
                    @if($list)
                        @foreach($list as $menuLevel1)
                            @if($menuLevel1->children)
                                <optgroup label="{{$menuLevel1->name}}">
                                    @foreach($menuLevel1->children as $menuLevel2)
                                        @if($menuLevel2->id == $data['menu_id'])
                                            <option value="{{$menuLevel2->id}}" selected="selected">{{$menuLevel2->name}}</option>
                                        @else
                                            <option value="{{$menuLevel2->id}}">{{$menuLevel2->name}}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @else
                                @if($menuLevel1->id == $data['menu_id'])
                                    <option value="{{$menuLevel1->id}}" selected="selected">{{$menuLevel1->name}}</option>
                                @else
                                    <option value="{{$menuLevel1->id}}">{{$menuLevel1->name}}</option>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline">
                <textarea name="desc" placeholder="请输入角色简介" class="layui-textarea">{{$data->desc}}</textarea>
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('permission.update')
            <input type="button" lay-submit lay-filter="admin-permission-submit" id="admin-permission-submit" value="确认">
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
