@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">意见反馈列表</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">姓名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" class="layui-input" autocomplete="off" placeholder="请输入姓名">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-block">
                        <input type="text" name="mobile" class="layui-input" autocomplete="off" placeholder="请输入手机号">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-block">
                        <input type="text" name="email" class="layui-input" autocomplete="off" placeholder="请输入邮箱">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-feedback-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-feedback-table" lay-filter="admin-feedback-table"></table>

            <script type="text/html" id="feedback-table-switchTpl">
                <input type="checkbox" lay-skin="switch" lay-text="已处理|未处理" lay-filter="feedback-table-status"
                       value="@{{ d.id }}" @{{ d.status == 1 ? 'checked' : '' }}>
            </script>
            <script type="text/html" id="admin-feedback-table-bar">
                @can('feedback.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
                @endcan
                @can('feedback.destroy')
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
        }).use(['index', 'form', 'table'], function(){
            var $ = layui.$,
                form = layui.form,
                table = layui.table;

            table.render({
                elem: '#admin-feedback-table',
                url: 'feedback/get_list',
                toolbar: '#admin-feedback-table-toolbar',
                autoSort: false,
                title: '意见反馈列表',
                cols: [[
                    {field:'id', width:80, title: 'ID', sort: true},
                    {field:'name', width:120, title: '姓名'},
                    {field:'mobile', width:140, title: '手机号'},
                    {field:'email', width:200, title: '邮箱'},
                    {field:'status', width:140, title: '状态',templet: '#feedback-table-switchTpl', unresize: true},
                    {field:'create_time', width:180, title: '反馈日期'},
                    {field:'remark', width:300, title: '备注', edit:'text'},
                    {field:'manager', width:140, title: '处理人'},
                    {field:'deal_time', width:180, title: '处理日期'},
                    {fixed: 'right', title:'操作', toolbar: '#admin-feedback-table-bar', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-feedback-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-feedback-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-feedback-table)', function (obj) {
                table.reload('admin-feedback-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            });

            //监听行工具事件
            table.on('tool(admin-feedback-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除吗？', function(index){
                        //提交数据
                        $.ajax({
                            url: 'feedback/'+data.id,
                            method: 'post',
                            data:{_method:'DELETE', _token:"{{csrf_token()}}"},
                            success: function (res) {
                                if(res.code === 0){
                                    var msg = res.msg ? res.msg : '删除成功';
                                    layer.msg(msg);
                                    table.reload('admin-feedback-table'); //数据刷新
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
                        title: '编辑意见反馈',
                        content: 'feedback/'+id+'/edit',
                        area: ['630px', '550px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-feedback-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'feedback/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-feedback-table'); //数据刷新
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

            //监听switch操作
            form.on('switch(feedback-table-status)', function(obj){
                var id = obj.value;
                var check_status = obj.elem.checked;
                var check_value = 0;
                if(check_status){
                    check_value = 1;
                }
                $.ajax({
                    url:'/admin/feedback/change_value',
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

            //编辑单元格内容
            table.on('edit(admin-feedback-table)',function (obj) {
                var id = obj.data.id;
                var field = obj.field;
                var value = obj.value;
                $.ajax({
                    url:'/admin/feedback/change_value',
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
