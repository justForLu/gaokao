@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">高校在各省分数线</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
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
                    <label class="layui-form-label">年份</label>
                    <div class="layui-input-block">
                        <input type="text" name="year" class="layui-input" id="enter_line-laydate-year" placeholder="yyyy">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">省份</label>
                    <div class="layui-input-block">
                        <div class="layui-inline">
                            <select name="province">
                                <option value="">请选择省份</option>
                                @if(!empty($province))
                                    @foreach($province as $item)
                                        <option value="{{$item['id']}}">{{$item['title']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">文理科</label>
                    <div class="layui-input-block">
                        {{\App\Enums\ScienceEnum::enumSelect(false,'请选择文理科','science')}}
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">批次</label>
                    <div class="layui-input-block">
                        {{\App\Enums\BatchEnum::enumSelect(false,'请选择批次','batch')}}
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-enter_line-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-enter_line-table" lay-filter="admin-enter_line-table"></table>

            <script type="text/html" id="admin-enter_line-table-toolbar">
                <div class="layui-btn-container">
                    @can('enter_line.create')
                    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="admin-enter_line-table-bar">
                @can('enter_line.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
                @endcan
                @can('enter_line.destroy')
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
        }).use(['index', 'form', 'table','laydate'], function(){
            var $ = layui.$,
                form = layui.form,
                table = layui.table,
                laydate = layui.laydate;

            table.render({
                elem: '#admin-enter_line-table',
                url: 'enter_line/get_list',
                toolbar: '#admin-enter_line-table-toolbar',
                autoSort: false,
                title: '高校在各省分数线',
                cols: [[
                    {field:'id', width:80, title: 'ID', sort: true},
                    {field:'school_name', title: '高校名称'},
                    {field:'province_name', title: '省份'},
                    {field:'year', title: '年份'},
                    {field:'science_name', title: '文理科'},
                    {field:'batch_name', title: '批次'},
                    {field:'min_score', title: '最低分', edit:'text'},
                    {field:'min_rank', title: '最低位次', edit:'text'},
                    {field:'control_line', title: '省控线', edit:'text'},
                    {fixed: 'right', title:'操作', toolbar: '#admin-enter_line-table-bar', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-enter_line-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-enter_line-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-enter_line-table)', function (obj) {
                table.reload('admin-enter_line-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            });
            //年选择器
            laydate.render({
                elem: '#enter_line-laydate-year',
                type: 'year'
            });

            //监听行工具事件
            table.on('tool(admin-enter_line-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除吗？', function(index){
                        //提交数据
                        $.ajax({
                            url: 'enter_line/'+data.id,
                            method: 'post',
                            data:{_method:'DELETE', _token:"{{csrf_token()}}"},
                            success: function (res) {
                                if(res.code === 0){
                                    var msg = res.msg ? res.msg : '删除成功';
                                    layer.msg(msg);
                                    table.reload('admin-enter_line-table'); //数据刷新
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
                        title: '编辑高校在各省分数线',
                        content: 'enter_line/'+id+'/edit',
                        area: ['550px', '600px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-enter_line-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'enter_line/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-enter_line-table'); //数据刷新
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
            table.on('toolbar(admin-enter_line-table)', function(obj){
                switch(obj.event){
                    case 'add':   //添加
                        layer.open({
                            type: 2,
                            title: '添加高校在各省分数线',
                            content: 'enter_line/create',
                            area: ['550px', '600px'],
                            btn: ['确定', '取消'],
                            yes: function(index, layero){
                                var iframeWindow = window['layui-layer-iframe'+ index],
                                    submitID = 'admin-enter_line-submit',
                                    submit = layero.find('iframe').contents().find('#'+ submitID);

                                //监听提交
                                iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                    var field = data.field; //获取提交的字段

                                    //提交数据
                                    $.ajax({
                                        url: 'enter_line',
                                        method: 'post',
                                        data: field,
                                        success: function (res) {
                                            if(res.code === 0){
                                                var msg = res.msg ? res.msg : '添加成功';
                                                layer.close(index); //关闭弹层
                                                layer.msg(msg);
                                                table.reload('admin-enter_line-table'); //数据刷新
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
            table.on('edit(admin-enter_line-table)',function (obj) {
                var id = obj.data.id;
                var field = obj.field;
                var value = obj.value;
                $.ajax({
                    url:'/admin/enter_line/change_value',
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
