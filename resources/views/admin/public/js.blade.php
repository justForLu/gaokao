<script src="{{asset("/assets/plugins/layuiadmin/layui/layui.js")}}"></script>
<script>
    layui.config({
        base: "{{asset('/assets/plugins/layuiadmin/')}}/" //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index','form'], function () {
        var $ = layui.$,
            form = layui.form;
        //加载主页
        layer.ready(function(){
            top.layui.index.openTabsPage("/admin/homepage",'首页');
        });
        //退出登录
        form.on('submit(admin_manager_logout)', function (obj) {
            $.ajax({
                url:'/admin/logout',
                method:'get',
                success:function (res) {
                    if(res.code === 0){
                        var msg = res.msg ? res.msg : '退出成功';
                        layer.msg(msg, function () {
                            location.href = '/admin/login'
                        })
                    }else{
                        var msg = res.msg ? res.msg : '退出失败';
                        layer.msg(msg)
                    }
                }
            })
        })

    });
</script>

