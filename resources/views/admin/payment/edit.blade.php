@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-payment" id="layuiadmin-form-payment" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>支付方式名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="请输入支付方式名称" value="{{$data->name}}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>支付方式图标</label>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="payment-upload-normal">上传图片</button>
                    <div class="layui-upload-list">
                        <img src="{{$data->icon}}" class="layui-upload-img" id="payment-upload-normal-img" style="width: 150px; height: 150px;">
                        <p id="test-upload-demoText"></p>
                    </div>
                    <input type="hidden" name="icon" value="{{$data->icon}}" id="payment_icon_path">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">支付方式描述</label>
            <div class="layui-input-block">
                <textarea name="describe" placeholder="请输入内容" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input type="text" name="sort" placeholder="请输入排序" value="{{$data->sort}}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                {{\App\Enums\BasicEnum::enumSelect($data->status,false,'status')}}
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('payment.update')
            <input type="button" lay-submit lay-filter="admin-payment-submit" id="admin-payment-submit" value="确认">
            @endcan
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        layui.config({
            base: "{{asset('/assets/plugins/layuiadmin/')}}/" //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'form', 'upload'], function(){
            var $ = layui.$,
                form = layui.form,
                upload = layui.upload;

            //支付方式图标上传
            var uploadInst = upload.render({
                elem: '#payment-upload-normal',
                url: '/admin/file/uploadPic',
                data: {_token:"{{csrf_token()}}"},
                before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#payment-upload-normal-img').attr('src', result); //图片链接（base64）
                    });
                },
                done: function(res){
                    //如果上传失败
                    if(res.code > 0){
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    if(res.code === 0){
                        $("#payment_icon_path").val(res.data.path);
                        layer.msg('上传成功');
                    }
                },
                error: function(){
                    //演示失败状态，并实现重传
                    var demoText = $('#test-upload-demoText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function(){
                        uploadInst.upload();
                    });
                }
            });
        })
    </script>
@endsection
