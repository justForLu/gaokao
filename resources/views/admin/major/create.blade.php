@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-major" id="layuiadmin-form-major" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>高校</label>
            <div class="layui-input-block">
                @if(!empty($school))
                    <select name="school_id" lay-verify="required" lay-search="">
                        @foreach($school as $v)
                            <option value="{{$v['id']}}">{{$v['name']}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>专业分类</label>
            <div class="layui-input-block">
                @if(!empty($category))
                    <select name="category_id" lay-verify="required" lay-search="">
                        @foreach($category as $v)
                            <option value="{{$v['id']}}">{{$v['name']}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>专业名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" placeholder="请输入专业名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">专业等级</label>
            <div class="layui-input-inline">
                {{\App\Enums\MajorTypeEnum::enumSelect(false,'请选择专业类别','type')}}
                <div class="layui-form-mid layui-word-aux">普通专业可不选</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">学科等级</label>
            <div class="layui-input-inline">
                {{\App\Enums\MajorEnum::enumSelect(false,'请选择学科等级','grade')}}
                <div class="layui-form-mid layui-word-aux">普通专业可不选</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>学制(年)</label>
            <div class="layui-input-block">
                <input type="text" name="edu_system" lay-verify="required|number" placeholder="请输入学制(年)" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">请填写数字</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="text" name="sort" placeholder="请输入排序" value="0" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">排序越大越靠前</div>
            </div>
        </div>
        <div class="layui-form-item" style="width: 90%; margin: 0px auto; background-color: #ffffff;">
            <div id="editor" style="margin: 50px 0 50px 0">

            </div>
            <textarea name="content" id="major-content" style="display: none;"></textarea>
        </div>
        <div class="layui-form-item layui-hide">
            @can('major.store')
            <input type="button" lay-submit lay-filter="admin-major-submit" id="admin-major-submit" value="确认">
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
        }).use(['index', 'form','wangEditor'], function(){
            var $ = layui.$,
                form = layui.form,
                wangEditor = layui.wangEditor;

            var editor = new wangEditor('#editor');
            editor.customConfig.uploadImgServer = "/admin/file/editUploadPic";
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
            var $article = $("#major-content");
            editor.customConfig.onchange = function (html) {
                // 监控变化，同步更新到 textarea
                $article.val(html)
            };

            editor.create();
        })
    </script>
@endsection
