@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">高校专业</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">专业名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" placeholder="请输入专业名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">高校</label>
                    <div class="layui-input-block">
                        <div class="layui-inline">
                            <select name="school_id">
                                <option value="">请选择高校</option>
                                @if(!empty($school))
                                    @foreach($school as $item)
                                        <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">专业分类</label>
                    <div class="layui-input-block">
                        <div class="layui-inline">
                            <select name="school_id">
                                <option value="">请选择专业分类</option>
                                @if(!empty($category))
                                    @foreach($category as $item)
                                        <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">专业等级</label>
                    <div class="layui-input-block">
                        {{\App\Enums\MajorTypeEnum::enumSelect(false,'请选择专业等级','type')}}
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">学科等级</label>
                    <div class="layui-input-block">
                        {{\App\Enums\MajorEnum::enumSelect(false,'请选择学科等级','grade')}}
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-major-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-major-table" lay-filter="admin-major-table"></table>

            <script type="text/html" id="admin-major-table-toolbar">
                <div class="layui-btn-container">
                    @can('major.create')
                    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="admin-major-table-bar">
                @can('major.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
                @endcan
                @can('major.destroy')
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
                elem: '#admin-major-table',
                url: 'major/get_list',
                toolbar: '#admin-major-table-toolbar',
                autoSort: false,
                title: '高校专业',
                cols: [[
                    {field:'id', width:80, title: 'ID', sort: true},
                    {field:'school_name', title: '高校名称'},
                    {field:'category_name', title: '分类'},
                    {field:'name', title: '专业名称'},
                    {field:'type_name', title: '专业等级'},
                    {field:'grade_name', title: '学科等级'},
                    {field:'edu_system', title: '学制(年)'},
                    {fixed: 'right', title:'操作', toolbar: '#admin-major-table-bar', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-major-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-major-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-major-table)', function (obj) {
                table.reload('admin-major-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            });

            //监听行工具事件
            table.on('tool(admin-major-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除吗？', function(index){
                        //提交数据
                        $.ajax({
                            url: 'major/'+data.id,
                            method: 'post',
                            data:{_method:'DELETE', _token:"{{csrf_token()}}"},
                            success: function (res) {
                                if(res.code === 0){
                                    var msg = res.msg ? res.msg : '删除成功';
                                    layer.msg(msg);
                                    table.reload('admin-major-table'); //数据刷新
                                }else{
                                    var msg = res.msg ? res.msg : '删除失败';
                                    layer.msg(msg);
                                }
                            }
                        });
                        layer.close(index); //关闭弹层
                    });
                } else if(obj.event === 'edit'){
                    var id = data.id;
                    layer.open({
                        type: 2,
                        title: '编辑高校专业',
                        content: 'major/'+id+'/edit',
                        area: ['800px', '600px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-major-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段
                                //提交数据
                                $.ajax({
                                    url: 'major/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-major-table'); //数据刷新
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
            table.on('toolbar(admin-major-table)', function(obj){
                switch(obj.event){
                    case 'add':   //添加
                        layer.open({
                            type: 2,
                            title: '添加高校专业',
                            content: 'major/create',
                            area: ['800px', '600px'],
                            btn: ['确定', '取消'],
                            yes: function(index, layero){
                                var iframeWindow = window['layui-layer-iframe'+ index],
                                    submitID = 'admin-major-submit',
                                    submit = layero.find('iframe').contents().find('#'+ submitID);

                                //监听提交
                                iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                    var field = data.field; //获取提交的字段

                                    //提交数据
                                    $.ajax({
                                        url: 'major',
                                        method: 'post',
                                        data: field,
                                        success: function (res) {
                                            if(res.code === 0){
                                                var msg = res.msg ? res.msg : '添加成功';
                                                layer.close(index); //关闭弹层
                                                layer.msg(msg);
                                                table.reload('admin-major-table'); //数据刷新
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
