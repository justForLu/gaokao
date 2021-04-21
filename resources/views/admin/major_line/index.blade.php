@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">高校各专业在各省分数线</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">高校</label>
                    <div class="layui-input-block">
                        <div class="layui-inline">
                            <select name="school_id" lay-filter="school">
                                <option value="">请选择高校</option>
                                @if(!empty($school))
                                    @foreach($school as $item)
                                        <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="layui-inline">
                            <select name="major_id" id="major" lay-search="">
                                <option value="">请选择专业</option>
                            </select>
                        </div>
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
                    <label class="layui-form-label">年份</label>
                    <div class="layui-input-block">
                        <input type="text" name="year" class="layui-input" id="major_line-laydate-year" placeholder="yyyy">
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
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-major_line-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-major_line-table" lay-filter="admin-major_line-table"></table>

            <script type="text/html" id="admin-major_line-table-toolbar">
                <div class="layui-btn-container">
                    @can('major_line.create')
                    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="admin-major_line-table-bar">
                @can('major_line.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
                @endcan
                @can('major_line.destroy')
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
                elem: '#admin-major_line-table',
                url: 'major_line/get_list',
                toolbar: '#admin-major_line-table-toolbar',
                autoSort: false,
                title: '高校各专业在各省分数线',
                cols: [[
                    {field:'id', width:80, title: 'ID'},
                    {field:'school_name', width:200, title: '高校名称'},
                    {field:'major_name', width:200, title: '专业名称'},
                    {field:'province_name', width:130, title: '省份'},
                    {field:'year', width:120, title: '年份'},
                    {field:'science_name', width:110, title: '文理科'},
                    {field:'batch_name', width:120, title: '批次'},
                    {field:'min_score', width:120, title: '最低分', edit:'text'},
                    {field:'min_rank', width:120, title: '最低位次', edit:'text'},
                    {field:'recruit_num', width:120, title: '招生人数', edit:'text'},
                    {field:'max_score', width:120, title: '最高分'},
                    {field:'avg_score', width:120, title: '平均分'},
                    {field:'sign_num', width:120, title: '报考人数'},
                    {field:'enter_num', width:120, title: '录取人数'},
                    {fixed: 'right', title:'操作', toolbar: '#admin-major_line-table-bar', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-major_line-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-major_line-table', {
                    where: field
                });
            });
            //高校专业联动
            var school_id = 0;
            form.on('select(school)', function (obj) {
                var school = obj.value;
                //下拉框值发生改变时
                if(school !== school_id){
                    school_id = school;
                    $.ajax({
                        url:'/admin/get_major_list',
                        method:'get',
                        data: {school_id:school_id},
                        success: function (res) {
                            //城市下拉框更新
                            var html_major = "<option value=''>请选择专业</option>";
                            if(res.data){
                                $.each(res.data, function (i,j) {
                                    html_major += "<option value='"+j.id+"'>" + j.name + "</option>";
                                });
                            }
                            $("#major").html(html_major);
                            form.render();
                        },
                        fail: function () {
                            //城市下拉框清空
                            var html_major = "<option value=''>请选择专业</option>";
                            $("#major").html(html_major);
                            form.render();
                        }
                    });
                }
            });
            //年选择器
            laydate.render({
                elem: '#major_line-laydate-year',
                type: 'year'
            });

            //监听行工具事件
            table.on('tool(admin-major_line-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除吗？', function(index){
                        //提交数据
                        $.ajax({
                            url: 'major_line/'+data.id,
                            method: 'post',
                            data:{_method:'DELETE', _token:"{{csrf_token()}}"},
                            success: function (res) {
                                if(res.code === 0){
                                    var msg = res.msg ? res.msg : '删除成功';
                                    layer.msg(msg);
                                    table.reload('admin-major_line-table'); //数据刷新
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
                        title: '编辑高校各专业在各省分数线',
                        content: 'major_line/'+id+'/edit',
                        area: ['660px', '600px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-major_line-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'major_line/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-major_line-table'); //数据刷新
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
            table.on('toolbar(admin-major_line-table)', function(obj){
                switch(obj.event){
                    case 'add':   //添加
                        layer.open({
                            type: 2,
                            title: '添加高校各专业在各省分数线',
                            content: 'major_line/create',
                            area: ['660px', '600px'],
                            btn: ['确定', '取消'],
                            yes: function(index, layero){
                                var iframeWindow = window['layui-layer-iframe'+ index],
                                    submitID = 'admin-major_line-submit',
                                    submit = layero.find('iframe').contents().find('#'+ submitID);

                                //监听提交
                                iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                    var field = data.field; //获取提交的字段

                                    //提交数据
                                    $.ajax({
                                        url: 'major_line',
                                        method: 'post',
                                        data: field,
                                        success: function (res) {
                                            if(res.code === 0){
                                                var msg = res.msg ? res.msg : '添加成功';
                                                layer.close(index); //关闭弹层
                                                layer.msg(msg);
                                                table.reload('admin-major_line-table'); //数据刷新
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
            table.on('edit(admin-major_line-table)',function (obj) {
                var id = obj.data.id;
                var field = obj.field;
                var value = obj.value;
                $.ajax({
                    url:'/admin/major_line/change_value',
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
