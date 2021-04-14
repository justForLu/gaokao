@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">日志列表</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">账号</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" placeholder="请输入账号" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-log-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-log-table" lay-filter="admin-log-table"></table>
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
                elem: '#admin-log-table',
                url: 'log/get_list',
                autoSort: false,
                title: '日志列表',
                cols: [[
                    {field:'id', width:80, title: 'ID', sort: true},
                    {field:'username', title: '操作人'},
                    {field:'content', title: '操作内容'},
                    {field:'ip', title: '操作IP'},
                    {field:'create_time', title: '操作时间'}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-log-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-log-table', {
                    where: field
                });
            });

            //监听排序
            table.on('sort(admin-log-table)', function (obj) {
                table.reload('admin-log-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            })
        });
    </script>
@endsection
