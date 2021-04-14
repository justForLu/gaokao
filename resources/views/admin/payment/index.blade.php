@extends('admin.layout.base')

@section('content')
    <style type="text/css">
        .layui-table-cell{
            height:50px;
            line-height: 50px;
            text-align: center;
        }
    </style>
    <div class="layui-card">
        <div class="layui-card-header">支付方式列表</div>

        <div class="layui-card-body">
            <table id="admin-payment-table" lay-filter="admin-payment-table"></table>

            <script type="text/html" id="payment-table-switchTpl">
                <input type="checkbox" lay-skin="switch" lay-text="启用|禁用" lay-filter="payment-table-status"
                       value="@{{ d.id }}" @{{ d.status == 1 ? 'checked' : '' }}>
            </script>
            <script type="text/html" id="admin-payment-table-bar">
                @can('payment.edit')
                <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="fa fa-pencil"></i>编辑</a>
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
                elem: '#admin-payment-table',
                url: 'payment/get_list',
                autoSort: false,
                title: '支付方式列表',
                cols: [[
                    {field:'id', width:80, title: 'ID'},
                    {field:'name', title: '支付方式名称',edit:'text'},
                    {field:'icon', title: '支付方式图标', templet: function(d) { return '<div><img src="'+d.icon+'" ' + 'alt="" width="45px" height="45px"></a></div>'; }},
                    {field:'sort', title: '排序', sort: true},
                    {field:'status', title: '状态',templet: '#payment-table-switchTpl', unresize: true},
                    {fixed: 'right', title:'操作', toolbar: '#admin-payment-table-bar', width:150}
                ]],
                limits: [10, 20, 50, 100],
                limit: 10,
                page: true
            });

            //监听排序
            table.on('sort(admin-payment-table)', function (obj) {
                table.reload('admin-payment-table', {
                    initSort: obj, //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                    where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                        sortBy: obj.field, //排序字段
                        sortType: obj.type //排序方式
                    }
                });
            })

            //监听行工具事件
            table.on('tool(admin-payment-table)', function(obj){
                var data = obj.data;
                if(obj.event === 'edit'){
                    var id = data.id;
                    layer.open({
                        type: 2,
                        title: '编辑支付方式',
                        content: 'payment/'+id+'/edit',
                        area: ['550px', '720px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index],
                                submitID = 'admin-payment-submit',
                                submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field; //获取提交的字段

                                //提交数据
                                $.ajax({
                                    url: 'payment/'+field.id,
                                    method: 'post',
                                    data: field,
                                    success: function (res) {
                                        if(res.code === 0){
                                            var msg = res.msg ? res.msg : '编辑成功';
                                            layer.close(index); //关闭弹层
                                            layer.msg(msg);
                                            table.reload('admin-payment-table'); //数据刷新
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
            table.on('edit(admin-payment-table)',function (obj) {
                var id = obj.data.id;
                var field = obj.field;
                var value = obj.value;
                $.ajax({
                    url:'/admin/payment/change_value',
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
            form.on('switch(payment-table-status)', function(obj){
                var id = obj.value;
                var check_status = obj.elem.checked;
                var check_value = 2;
                if(check_status){
                    check_value = 1;
                }
                $.ajax({
                    url:'/admin/payment/change_value',
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
