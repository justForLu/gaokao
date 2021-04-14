@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">文章列表</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">文章标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" placeholder="请输入文章标题" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-article-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-article-table" lay-filter="admin-article-table"></table>

            <script type="text/html" id="article-table-switchTpl">
                <input type="checkbox" lay-skin="switch" lay-text="启用|禁用" lay-filter="article-table-status"
                       value="@{{ d.id }}" @{{ d.status == 1 ? 'checked' : '' }}>
            </script>
            <script type="text/html" id="admin-article-table-toolbar">
                <div class="layui-btn-container">
                    @can('article.create')
                    <a class="layui-btn layui-btn-sm" lay-event="add">添加</a>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="admin-article-table-bar">
                @can('article.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
                @endcan
                @can('article.destroy')
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
                elem: '#admin-article-table',
                url: 'article/get_list',
                toolbar: '#admin-article-table-toolbar',
                autoSort: false,
                title: '配置列表',
                cols: [[
                    {checkbox: true, fixed: true},
                    {field:'id', width:120, title: 'ID', sort: true},
                    {field:'title', title: '文章标题',edit:'text'},
                    {field:'type_name', width:160, title: '类型'},
                    {field:'status',  width:160, title: '状态',templet: '#article-table-switchTpl', unresize: true},
                    {field:'sort',  width:120, title: '排序'},
                    {fixed: 'right', title:'操作', toolbar: '#admin-article-table-bar', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-article-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-article-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-article-table)', function (obj) {
                table.reload('admin-article-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            });

            //监听行工具事件
            table.on('tool(admin-article-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除吗？', function(index){
                        //提交数据
                        $.ajax({
                            url: 'article/'+data.id,
                            method: 'post',
                            data:{_method:'DELETE', _token:"{{csrf_token()}}"},
                            success: function (res) {
                                if(res.code === 0){
                                    var msg = res.msg ? res.msg : '删除成功';
                                    layer.msg(msg);
                                    table.reload('admin-article-table'); //数据刷新
                                }else{
                                    var msg = res.msg ? res.msg : '删除失败';
                                    layer.msg(msg);
                                }
                            }
                        })
                        layer.close(index); //关闭弹层
                    });
                }else if(obj.event === 'edit'){
                    var id = data.id;
                    layer.open({
                        type: 2,
                        title: '编辑文章',
                        content: 'article/'+id+'/edit',
                        area: ['900px', '810px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-article-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'article/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-article-table'); //数据刷新
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
            table.on('toolbar(admin-article-table)', function(obj){
                switch(obj.event){
                    case 'add':   //添加
                        layer.open({
                            type: 2,
                            title: '添加文章',
                            content: 'article/create',
                            area: ['900px', '810px'],
                            btn: ['确定', '取消'],
                            yes: function(index, layero){
                                var iframeWindow = window['layui-layer-iframe'+ index],
                                    submitID = 'admin-article-submit',
                                    submit = layero.find('iframe').contents().find('#'+ submitID);

                                //监听提交
                                iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                    var field = data.field; //获取提交的字段

                                    //提交数据
                                    $.ajax({
                                        url: 'article',
                                        method: 'post',
                                        data: field,
                                        success: function (res) {
                                            if(res.code === 0){
                                                var msg = res.msg ? res.msg : '添加成功';
                                                layer.close(index); //关闭弹层
                                                layer.msg(msg);
                                                table.reload('admin-article-table'); //数据刷新
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
            //编辑单元格内容
            table.on('edit(admin-article-table)',function (obj) {
                var id = obj.data.id;
                var field = obj.field;
                var value = obj.value;
                $.ajax({
                    url:'/admin/article/change_value',
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
            //监听switch操作
            form.on('switch(article-table-status)', function(obj){
                var id = obj.value;
                var check_status = obj.elem.checked;
                var check_value = 2;
                if(check_status){
                    check_value = 1;
                }
                $.ajax({
                    url:'/admin/article/change_value',
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
