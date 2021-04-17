@extends('admin.layout.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header">用户列表</div>
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-block">
                        <input type="text" name="mobile" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-block">
                        <input type="text" name="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">昵称</label>
                    <div class="layui-input-block">
                        <input type="text" name="nickname" placeholder="请输入昵称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-block">
                        {{\App\Enums\BasicEnum::enumSelect(false,'请选择状态','status')}}
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="admin-user-table-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="admin-user-table" lay-filter="admin-user-table"></table>

            <script type="text/html" id="user-table-switchTpl">
                <input type="checkbox" lay-skin="switch" lay-text="正常|冻结" lay-filter="user-table-freeze"
                       value="@{{ d.id }}" @{{ d.is_freeze === 0 ? 'checked' : '' }}>
            </script>
            <script type="text/html" id="admin-user-table-bar">
                @can('user.show')
                <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="show"><i class="fa fa-search"></i>查看</a>
                @endcan
                @can('user.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-search"></i>编辑</a>
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
                elem: '#admin-user-table',
                url: 'user/get_list',
                autoSort: false,
                title: '用户列表',
                cols: [[
                    {field:'id', width:80, title: 'ID'},
                    {field:'username', width:190,  title: '用户名'},
                    {field:'nickname', width:190, title: '昵称'},
                    {field:'mobile', width:190, title: '手机号'},
                    {field:'email', width:240, title: '邮箱'},
                    {field:'create_time', width:220, title: '注册时间'},
                    {field:'login_time', width:220, title: '上次登录时间'},
                    {field:'status', width:140, title: '状态',templet:function (d) {
                            if (d.status == 1) {
                                return '<span style="color:#008000;">正常</span>'
                            }else if(d.status == 2){
                                return '<span style="color:#f44336;">禁用</span>'
                            }else{
                                return '<span style="color:#999999;">-</span>'
                            }
                        }},
                    {fixed: 'right', title:'操作', toolbar: '#admin-user-table-bar', width:160}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });
            //监听搜索
            form.on('submit(admin-user-table-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('admin-user-table', {
                    where: field
                });
            });
            //监听排序
            table.on('sort(admin-user-table)', function (obj) {
                table.reload('admin-user-table', {
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
            var city_id = 0;
            form.on('select(city)', function (obj) {
                var city = obj.value;
                if(city !== city_id){
                    city_id = city;
                    $.ajax({
                        url:'/admin/get_city_list',
                        method:'get',
                        data: {id:city_id},
                        success: function (res) {
                            //区县下拉框更新
                            var html_area = "<option value=''>请选择区/县</option>";
                            if(res){
                                $.each(res, function (i,j) {
                                    html_area += "<option value='"+j.id+"'>" + j.title + "</option>";
                                });
                            }
                            $("#area").html(html_area);
                            form.render();
                        },
                        fail: function () {
                            //区县下拉框清空
                            var html_area = "<option value=''>请选择区/县</option>";
                            $("#area").html(html_area);
                            form.render();
                        }
                    });
                }
            });
            //监听行工具事件
            table.on('tool(admin-user-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'show'){
                    var id = data.id;
                    layer.open({
                        type: 2,
                        title: '查看用户',
                        content: 'user/'+id,
                        area: ['800px', '750px']
                    });
                }else if(obj.event === 'edit'){
                    var id = data.id;
                    layer.open({
                        type: 2,
                        title: '编辑用户信息',
                        content: 'user/'+id+'/edit',
                        area: ['500px', '630px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-user-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'user/'+id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-user-table'); //数据刷新
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
                }else if(obj.event === 'recharge'){
                    var id = data.id;
                    var username = data.username;
                    layer.open({
                        type: 2,
                        title: '充值玉豆',
                        content: 'user/recharge/'+id+'?username='+username,
                        area: ['420px', '320px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-user-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'user/recharge/'+id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '充值成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-user-table'); //数据刷新
                                        }else{
                                            var msg = res.msg ? res.msg : '充值失败';
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
            table.on('toolbar(admin-user-table)', function(obj){
                switch(obj.event){
                    case 'add':   //添加
                        layer.open({
                            type: 2,
                            title: '添加用户',
                            content: 'user/create',
                            area: ['800px', '620px'],
                            btn: ['确定', '取消'],
                            yes: function(index, layero){
                                var iframeWindow = window['layui-layer-iframe'+ index],
                                    submitID = 'admin-user-submit',
                                    submit = layero.find('iframe').contents().find('#'+ submitID);

                                //监听提交
                                iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                    var field = data.field; //获取提交的字段

                                    //提交数据
                                    $.ajax({
                                        url: 'user',
                                        method: 'post',
                                        data: field,
                                        success: function (res) {
                                            if(res.code === 0){
                                                var msg = res.msg ? res.msg : '添加成功';
                                                layer.close(index); //关闭弹层
                                                layer.msg(msg);
                                                table.reload('admin-user-table'); //数据刷新
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
            //监听switch操作
            form.on('switch(user-table-freeze)', function(obj){
                var id = obj.value;
                var check_status = obj.elem.checked;
                var check_value = 1;
                if(check_status){
                    check_value = 0;
                }
                $.ajax({
                    url:'/admin/user/change_value',
                    method:'post',
                    data:{_token:"{{csrf_token()}}",id:id,field:'is_freeze',value:check_value},
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
