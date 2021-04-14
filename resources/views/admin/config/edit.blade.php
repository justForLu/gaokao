@extends('admin.layout.base')

@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>[ {{$data->name}} ]</legend>
                    </fieldset>
                    <div class="layui-form" lay-filter="layuiadmin-form-config" id="layuiadmin-form-config">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <input type="hidden" name="only_tag" value="{{$data->only_tag}}">

                        @if($data->is_rich == 1)
                            <div class="layui-form-item" style="width: 90%; margin: 0px auto; background-color: #ffffff;">
                                <div id="editor" style="margin: 50px 0 50px 0">
                                    <?php echo $data->value ?>
                                </div>
                                <textarea name="value" id="config-value" style="display: none;">{{$data->value}}</textarea>
                            </div>
                        @elseif($data->is_img == 1)
                            <div class="layui-form-item">
                                <label class="layui-form-label"><span class="required-star">*</span>分类图片</label>
                                <div class="layui-input-block">
                                    <div class="layui-upload">
                                        <button type="button" class="layui-btn" id="config-upload-normal">上传图片</button>
                                        <div class="layui-upload-list">
                                            <img src="{{$data->value}}" class="layui-upload-img" id="config-upload-normal-img" style="width: 150px; height: 150px;">
                                            <p id="test-upload-demoText"></p>
                                        </div>
                                        <input type="hidden" name="value" value="{{$data->value}}" id="config_image_path">
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="layui-form-item">
                                <label class="layui-form-label">{{$data->name}}</label>
                                <div class="layui-input-block">
                                    <div class="layui-col-md6">
                                        <input type="text" name="value" lay-verify="required" value="{{$data->value}}" placeholder="请输入{{$data->name}}" autocomplete="off" class="layui-input">
                                    </div>
                                    @if($manager_name == 'jiazong')
                                        <div class="layui-form-mid layui-word-aux" style="color: red !important;">{{$data->describe}}</div>
                                    @else
                                        <div class="layui-form-mid layui-word-aux">{{$data->describe}}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="layui-form-item layui-hide">
                            @if($manager_name == 'jiazong')
                                <input type="button" lay-submit lay-filter="admin-config-submit" id="admin-config-submit" value="确认">
                            @else
                                @can('config.update')
                                    <input type="button" lay-submit lay-filter="admin-config-submit" id="admin-config-submit" value="确认">
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        layui.config({
            base: "{{asset('/assets/plugins/layuiadmin/')}}/" //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'form','wangEditor','upload'], function(){
            var $ = layui.$,
                form = layui.form,
                wangEditor = layui.wangEditor,
                upload = layui.upload;

            //图片上传
            var uploadInst = upload.render({
                elem: '#config-upload-normal',
                url: '/admin/file/uploadPic',
                data: {_token:"{{csrf_token()}}"},
                before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#config-upload-normal-img').attr('src', result); //图片链接（base64）
                    });
                },
                done: function(res){
                    //如果上传失败
                    if(res.code > 0){
                        return layer.msg(res.msg);
                    }
                    //上传成功
                    if(res.code === 0){
                        $("#config_image_path").val(res.data.path);
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

            //富文本编辑器
            var editor = new wangEditor('#editor');
            editor.customConfig.uploadImgServer = "../api/upload.json";
            editor.customConfig.uploadFileName = 'image';
            editor.customConfig.pasteFilterStyle = false;
            editor.customConfig.uploadImgMaxLength = 5;
            editor.customConfig.uploadImgHooks = {
                // 上传超时
                timeout: function (xhr, editor) {
                    layer.msg('上传超时！')
                },
                // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
                customInsert: function (insertImg, result, editor) {
                    console.log(result);
                    if (result.code == 1) {
                        var url = result.data.url;
                        url.forEach(function (e) {
                            insertImg(e);
                        })
                    } else {
                        layer.msg(result.msg);
                    }
                }
            };
            editor.customConfig.customAlert = function (info) {
                layer.msg(info);
            };

            var $config_value = $("#config-value");
            editor.customConfig.onchange = function (html) {
                // 监控变化，同步更新到 textarea
                $config_value.val(html)
            };

            editor.create();
        })
    </script>
@endsection
