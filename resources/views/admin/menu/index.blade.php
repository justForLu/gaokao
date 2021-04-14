@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">菜单列表</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">菜单名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-menu-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-menu-table" lay-filter="admin-menu-table"></table>

            <script type="text/html" id="admin-menu-table-toolbar">
                <div class="layui-btn-container">
                    @can('menu.create')
                    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="admin-menu-table-barDemo">
                @can('menu.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
                @endcan
                @can('menu.destroy')
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="fa fa-trash-o"></i>删除</a>
                @endcan
            </script>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        layui.config({
            base: "{{asset('/assets/plugins/layuiadmin/')}}/" //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'table'], function(){
            var $ = layui.$,
                form = layui.form,
                table = layui.table;

            table.render({
                elem: '#admin-menu-table',
                url: 'menu/get_list',
                toolbar: '#admin-menu-table-toolbar',
                autoSort: false,
                title: '菜单列表',
                cols: [[
                    {checkbox: true, fixed: true},
                    {field:'id', width:80, title: 'ID', sort: true},
                    {field:'name', title: '菜单名称'},
                    {field:'url', title: '菜单地址'},
                    {field:'code', title: '菜单编码'},
                    {field:'grade', title: '菜单等级'},
                    {field:'status_name', title: '菜单状态', minWidth: 100},
                    {fixed: 'right', title:'操作', toolbar: '#admin-menu-table-barDemo', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-menu-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-menu-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-menu-table)', function (obj) {
                table.reload('admin-menu-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            })

            //监听行工具事件
            table.on('tool(admin-menu-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除吗？', function(index){
                        //提交数据
                        $.ajax({
                            url: 'menu/'+data.id,
                            method: 'post',
                            data:{_method:'DELETE', _token:"{{csrf_token()}}"},
                            success: function (res) {
                                if(res.code === 0){
                                    var msg = res.msg ? res.msg : '删除成功';
                                    layer.msg(msg);
                                    table.reload('admin-menu-table'); //数据刷新
                                }else{
                                    var msg = res.msg ? res.msg : '删除失败';
                                    layer.msg(msg);
                                }
                            }
                        })
                        layer.close(index); //关闭弹层
                    });
                } else if(obj.event === 'edit'){
                    var id = data.id;
                    layer.open({
                        type: 2,
                        title: '编辑管理员',
                        content: 'menu/'+id+'/edit',
                        area: ['420px', '520px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-menu-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'menu/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-menu-table'); //数据刷新
                                        }else{
                                            var msg = res.msg ? res.msg : '编辑失败';
                                            layer.msg(msg);
                                        }
                                    }
                                })
                            });

                            submit.trigger('click');
                        }
                    });
                }
            });

            //头部工具栏
            table.on('toolbar(admin-menu-table)', function(obj){
                switch(obj.event){
                    case 'add':   //添加
                        layer.open({
                            type: 2,
                            title: '添加管理员',
                            content: 'menu/create',
                            area: ['420px', '520px'],
                            btn: ['确定', '取消'],
                            yes: function(index, layero){
                                var iframeWindow = window['layui-layer-iframe'+ index],
                                    submitID = 'admin-menu-submit',
                                    submit = layero.find('iframe').contents().find('#'+ submitID);

                                //监听提交
                                iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                    var field = data.field; //获取提交的字段

                                    //提交数据
                                    $.ajax({
                                        url: 'menu',
                                        method: 'post',
                                        data: field,
                                        success: function (res) {
                                            if(res.code === 0){
                                                var msg = res.msg ? res.msg : '添加成功';
                                                layer.close(index); //关闭弹层
                                                layer.msg(msg);
                                                table.reload('admin-menu-table'); //数据刷新
                                            }else{
                                                var msg = res.msg ? res.msg : '添加失败';
                                                layer.msg(msg);
                                            }
                                        }
                                    })
                                });

                                submit.trigger('click');
                            }
                        });
                        break;
                };
            });
        });
    </script>
@endsection
