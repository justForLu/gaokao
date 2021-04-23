@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">高校列表</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">高校名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" placeholder="请输入分类名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">省市县</label>
                    <div class="layui-input-block">
                        <div class="layui-inline">
                            <select name="province" id="province" lay-filter="province">
                                <option value="">请选择省份</option>
                                @if($province)
                                    @foreach($province as $item)
                                        <option value="{{$item['id']}}">{{$item['title']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="layui-inline">
                            <select name="city" id="city" lay-filter="city">
                                <option value="">请选择城市</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-school-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-school-table" lay-filter="admin-school-table"></table>

            <script type="text/html" id="school-table-switchTpl">
                <input type="checkbox" lay-skin="switch" lay-text="启用|禁用" lay-filter="school-table-status"
                    value="@{{ d.id }}" @{{ d.status == 1 ? 'checked' : '' }}>
            </script>
            <script type="text/html" id="admin-school-table-toolbar">
                <div class="layui-btn-container">
                    @can('school.create')
                    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="admin-school-table-bar">
                @can('school.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
                @endcan
                @can('school.destroy')
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
                elem: '#admin-school-table',
                url: 'school/get_list',
                toolbar: '#admin-school-table-toolbar',
                autoSort: false,
                title: '高校列表',
                cols: [[
                    {field:'id', width:80, title: 'ID', sort: true},
                    {field:'name', title: '高校名称', edit:'text'},
                    {field:'province_city', title: '省市县'},
                    {field:'address', title: '详细地址'},
                    {field:'belong', title: '隶属'},
                    {field:'sort', title: '排序'},
                    {field:'status', title: '状态',templet: '#school-table-switchTpl', unresize: true},
                    {fixed: 'right', title:'操作', toolbar: '#admin-school-table-bar', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-school-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-school-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-school-table)', function (obj) {
                table.reload('admin-school-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            });

            //省市县多级联动
            var province_id = 0;
            form.on('select(province)', function (obj) {
                var province = obj.value;
                //下拉框值发生改变时
                if(province !== province_id){
                    province_id = province;
                    $.ajax({
                        url:'/admin/get_city_list',
                        method:'get',
                        data: {id:province_id},
                        success: function (res) {
                            //城市下拉框更新
                            var html_city = "<option value=''>请选择城市</option>";
                            if(res){
                                $.each(res, function (i,j) {
                                    html_city += "<option value='"+j.id+"'>" + j.title + "</option>";
                                });
                            }
                            $("#city").html(html_city);
                            //区县下拉框清空
                            var html_area = "<option value=''>请选择区/县</option>";
                            $("#area").html(html_area);
                            form.render();
                        },
                        fail: function () {
                            //城市下拉框清空
                            var html_city = "<option value=''>请选择城市</option>";
                            $("#city").html(html_city);
                            //区县下拉框清空
                            var html_area = "<option value=''>请选择区/县</option>";
                            $("#area").html(html_area);
                            form.render();
                        }
                    });
                }
            });

            //监听行工具事件
            table.on('tool(admin-school-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除吗？', function(index){
                        //提交数据
                        $.ajax({
                            url: 'school/'+data.id,
                            method: 'post',
                            data:{_method:'DELETE', _token:"{{csrf_token()}}"},
                            success: function (res) {
                                if(res.code === 0){
                                    var msg = res.msg ? res.msg : '删除成功';
                                    layer.msg(msg);
                                    table.reload('admin-school-table'); //数据刷新
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
                        title: '编辑高校',
                        content: 'school/'+id+'/edit',
                        area: ['800px', '630px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-school-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'school/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-school-table'); //数据刷新
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
            table.on('toolbar(admin-school-table)', function(obj){
                switch(obj.event){
                    case 'add':   //添加
                        layer.open({
                            type: 2,
                            title: '添加高校',
                            content: 'school/create',
                            area: ['800px', '630px'],
                            btn: ['确定', '取消'],
                            yes: function(index, layero){
                                var iframeWindow = window['layui-layer-iframe'+ index],
                                    submitID = 'admin-school-submit',
                                    submit = layero.find('iframe').contents().find('#'+ submitID);

                                //监听提交
                                iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                    var field = data.field; //获取提交的字段

                                    //提交数据
                                    $.ajax({
                                        url: 'school',
                                        method: 'post',
                                        data: field,
                                        success: function (res) {
                                            if(res.code === 0){
                                                var msg = res.msg ? res.msg : '添加成功';
                                                layer.close(index); //关闭弹层
                                                layer.msg(msg);
                                                table.reload('admin-school-table'); //数据刷新
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
            table.on('edit(admin-school-table)',function (obj) {
                var id = obj.data.id;
                var field = obj.field;
                var value = obj.value;
                $.ajax({
                    url:'/admin/school/change_value',
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
            form.on('switch(school-table-status)', function(obj){
                var id = obj.value;
                var check_status = obj.elem.checked;
                var check_value = 2;
                if(check_status){
                    check_value = 1;
                }
                $.ajax({
                    url:'/admin/school/change_value',
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
