@extends('admin.layout.base')

@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">角色授权</div>
                <div class="layui-card-body">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>[ {{$role->name}} ]</legend>
                    </fieldset>
                    <div class="layui-form" lay-filter="admin-role-authority">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="role_id" value="{{ $params['role_id'] }}">
                        <table class="layui-table" lay-filter="role-authority-table" id="role-authority-table" lay-skin="row">
                            <colgroup>
                                <col width="20%">
                                <col width="80%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>菜单名称</th>
                                    <th>操作权限</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($menuList as $menu)
                                <tr>
                                    <td>{{$menu->name}}</td>
                                    <td></td>
                                </tr>
                                @if($menu->children)
                                    @foreach($menu->children as $menu1)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="menus[]" id="authority_{{$menu1->id}}" lay-filter="menu_checkbox" value="{{$menu1->id}}" title="{{$menu1->name}}" {{$menu1->checked}} lay-skin="primary">
                                            </td>
                                            <td>
                                                <div class="layui-row layui-col-space10">
                                                    <div class="layui-col-md12">
                                                        @foreach($menu1->permissions as $permission)
                                                            <input type="checkbox" name="permissions[]" lay-filter="authority_checkbox" class="authority_{{$menu1->id}}" value="{{$permission->id}}" title="{{$permission->name}}" {{$permission->checked}} lay-skin="primary">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        <div class="layui-footer" style="padding-top: 10px;">
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="admin-role-authority-submit">确定</button>
                                </div>
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
        }).use(['index', 'form', 'table'], function(){
            var $ = layui.$,
                form = layui.form ;

            //全选/全不选
            form.on('checkbox(menu_checkbox)', function(data){
                var child = $("." + data.elem.id);
                child.each(function (index, item) {
                    item.checked = data.elem.checked;
                });
                form.render('checkbox');

            });
            //权限复选框点击事件
            form.on('checkbox(authority_checkbox)', function(data){
                var authority_class = data.elem.className;
                var is_all_checked = true;
                var child = $("." + authority_class);
                child.each(function (index, item) {
                    if(!item.checked){
                        is_all_checked = false;
                    }
                });
                $("#"+authority_class).prop('checked', is_all_checked);
                form.render('checkbox');
            });

            //授权操作
            form.on('submit(admin-role-authority-submit)', function (obj) {
                var data = obj.field;
                $.ajax({
                    url:'authority',
                    method: 'post',
                    data: data,
                    success: function (res) {
                        if(res.code === 0){
                            var msg = res.msg ? res.msg : '操作成功';
                            layer.msg(msg,function () {
                                location.href = '/admin/role/authority/'+{{$params['role_id']}};
                            })
                        }else{
                            var msg = res.msg ? res.msg : '操作失败';
                            layer.msg(msg);
                        }
                    }
                })
            })
        })
    </script>
@endsection
