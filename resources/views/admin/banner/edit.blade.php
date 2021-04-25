@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-banner" id="layuiadmin-form-banner" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>轮播图标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required" value="{{$data->title}}" placeholder="请输入轮播图标题" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>图片</label>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="banner-upload-normal">上传图片</button>
                    <div class="layui-upload-list">
                        <img src="{{$data->image}}" class="layui-upload-img" id="banner-upload-normal-img" style="width: 150px; height: 150px;">
                        <p id="test-upload-demoText"></p>
                    </div>
                    <input type="hidden" name="image" value="{{$data->image}}" id="banner_image_path">
                </div>
                <div class="layui-form-mid layui-word-aux">PC端建议尺寸1600*420，移动端建议尺寸</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">跳转地址</label>
            <div class="layui-input-block">
                <input type="text" name="url" placeholder="请输入跳转地址" value="{{$data->url}}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">终端</label>
            <div class="layui-input-inline">
                <div class="layui-input-inline">
                    {{\App\Enums\TermEnum::enumSelect($data->position,false,'terminal')}}
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">轮播图位置</label>
            <div class="layui-input-inline">
                <div class="layui-input-inline">
                    {{\App\Enums\PositionEnum::enumSelect($data->position,false,'position')}}
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input type="text" name="sort" value="{{$data->sort}}" placeholder="请输入排序" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">排序越大越靠前</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                {{\App\Enums\BasicEnum::enumSelect($data->status,false,'status')}}
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('banner.update')
            <input type="button" lay-submit lay-filter="admin-banner-submit" id="admin-banner-submit" value="确认">
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
        }).use(['index', 'form','upload'], function(){
            var $ = layui.$,
                form = layui.form,
                upload = layui.upload;

            //轮播图上传
            var uploadInst = upload.render({
                elem: '#banner-upload-normal',
                url: '/admin/file/uploadPic',
                data: {_token:"{{csrf_token()}}"},
                before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#banner-upload-normal-img').attr('src', result); //图片链接（base64）
                    });
                },
                done: function(res){
                    //如果上传失败
                    if(res.code > 0){
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    if(res.code === 0){
                        $("#banner_image_path").val(res.data.path);
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
