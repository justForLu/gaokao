<script src="{{asset("/assets/home/js/jquery.js")}}"></script>
<script src="{{asset("/assets/home/js/iealert.js")}}"></script>
<script src="{{asset("/assets/home/js/ckbrowser.js")}}"></script>
<script src="{{asset("/assets/home/js/main.js")}}"></script>
<script src="{{asset("/assets/home/js/swiper.js")}}"></script>
<script src="{{asset("/assets/home/js/Popt.js")}}"></script>
<script src="{{asset("/assets/home/js/citySet.js")}}"></script>
<script src="{{asset("/assets/plugins/validate/Validform_v5.3.2_min.js")}}"></script>
<script src="{{asset("/assets/plugins/layer/layer.js")}}"></script>
<script src="{{asset("/assets/home/js/form.js")}}"></script>
<script src="{{asset("/assets/plugins/layui/layui.js")}}"></script>
<script>
    layui.config({
        base: "{{asset('/assets/plugins/layui/')}}/" //静态资源所在路径
    }).use(['form'], function () {
        var $ = layui.$,
            form = layui.form;

    });
</script>


