@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">配置列表</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">配置名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" placeholder="请输入配置名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-config-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-config-table" lay-filter="admin-config-table"></table>

            <script type="text/html" id="admin-config-table-bar">
                @if($manager_name == 'jiazong')
                    <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>贾总编辑</a>
                @else
                    @can('config.edit')
                    <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
                    @endcan
                @endif
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
                elem: '#admin-config-table',
                url: 'config/get_list',
                autoSort: false,
                title: '配置列表',
                cols: [[
                    {field:'id', width:80, title: 'ID', sort: true},
                    {field:'name', title: '配置名称', width:240, edit:'text'},
                    {field:'describe', title: '配置描述', width:380, edit:'text'},
                    {field:'value', title: '值'},
                    {fixed: 'right', title:'操作', toolbar: '#admin-config-table-bar', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-config-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-config-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-config-table)', function (obj) {
                table.reload('admin-config-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            });
            //监听行工具事件
            table.on('tool(admin-config-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'edit'){
                    var id = data.id;
                    layer.open({
                        type: 2,
                        title: '编辑配置',
                        content: 'config/'+id+'/edit',
                        area: ['900px', '620px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-config-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'config/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-config-table'); //数据刷新
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
            //编辑单元格内容
            table.on('edit(admin-config-table)',function (obj) {
                var id = obj.data.id;
                var field = obj.field;
                var value = obj.value;
                $.ajax({
                    url:'/admin/config/change_value',
                    method:'post',
                    data:{_token:"{{csrf_token()}}",id:id,field:field,value:value},
                    success: function (res) {
                        if(res.code === 0){
                            layer.msg('编辑成功');
                        }else{
                            layer.msg(res.msg);
                        }
                    },fail: function () {
                        layer.msg('编辑失败');
                    }
                });
            });
        });
    </script>
@endsection
