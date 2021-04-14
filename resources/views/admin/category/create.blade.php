@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-category" id="layuiadmin-form-category" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>分类名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" placeholder="请输入分类名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>分类图片</label>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="category-upload-normal">上传图片</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="category-upload-normal-img" style="width: 150px; height: 150px;">
                        <p id="test-upload-demoText"></p>
                    </div>
                    <input type="hidden" name="image" value="" id="category_image_path">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input type="text" name="sort" placeholder="请输入排序" value="0" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                {{\App\Enums\BasicEnum::enumSelect(\App\Enums\BasicEnum::ACTIVE,false,'status')}}
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('category.store')
            <input type="button" lay-submit lay-filter="admin-category-submit" id="admin-category-submit" value="确认">
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

            //轮播图上传
            var uploadInst = upload.render({
                elem: '#category-upload-normal',
                url: '/admin/file/uploadPic',
                data: {_token:"{{csrf_token()}}"},
                before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#category-upload-normal-img').attr('src', result); //图片链接（base64）
                    });
                },
                done: function(res){
                    //如果上传失败
                    if(res.code > 0){
                        return layer.msg(res.msg);
                    }
                    //上传成功
                    if(res.code === 0){
                        $("#category_image_path").val(res.data.path);
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
