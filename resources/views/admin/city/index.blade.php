@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">城市列表</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">城市名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" placeholder="请输入城市名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-city-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-city-table" lay-filter="admin-city-table"></table>

            <script type="text/html" id="city-table-switchTpl">
                <input type="checkbox" lay-skin="switch" lay-text="启用|禁用" lay-filter="city-table-status"
                       value="@{{ d.id }}" @{{ d.status == 1 ? 'checked' : '' }}>
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
        }).use(['index', 'form', 'table'], function(){
            var $ = layui.$,
                form = layui.form,
                table = layui.table;

            table.render({
                elem: '#admin-city-table',
                url: 'city/get_list',
                autoSort: false,
                title: '城市列表',
                cols: [[
                    {checkbox: true, fixed: true},
                    {field:'id', width:80, title: 'ID', sort: true},
                    {field:'title', title: '城市名称'},
                    {field:'grade', title: '等级'},
                    {field:'sort', title: '排序'},
                    {field:'status', title: '状态',templet: '#city-table-switchTpl', unresize: true},
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-city-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-city-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-city-table)', function (obj) {
                table.reload('admin-city-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            });
            //监听switch操作
            form.on('switch(city-table-status)', function(obj){
                var id = obj.value;
                var check_status = obj.elem.checked;
                var check_value = 2;
                if(check_status){
                    check_value = 1;
                }
                $.ajax({
                    url:'/admin/city/change_value',
                    method:'post',
                    data:{_token:"{{csrf_token()}}",id:id,field:'status',value:check_value},
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
