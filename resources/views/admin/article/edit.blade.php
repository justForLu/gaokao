@extends('admin.layout.base')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-config" id="layuiadmin-form-config" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>文章标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" placeholder="请输入文章标题" value="{{$data->title}}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文章类型</label>
            <div class="layui-input-inline">
                @if(!empty($category))
                    <select name="category_id">
                        @foreach($category as $v)
                            <option value="{{$v['id']}}" @if($v['id'] == $data->category_id) selected @endif>{{$v['name']}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文章简介</label>
            <div class="layui-input-block">
                <textarea name="introduce" placeholder="请输入文章简介" class="layui-textarea">{{$data->introduce}}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否推荐</label>
            <div class="layui-input-inline">
                {{\App\Enums\BoolEnum::enumRadio($data->is_recommend,'is_recommend')}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否置顶</label>
            <div class="layui-input-inline">
                {{\App\Enums\BoolEnum::enumRadio($data->is_top,'is_top')}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="text" name="sort" placeholder="请输入排序" value="{{$data->sort}}" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">排序越大越靠前</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                {{\App\Enums\BasicEnum::enumRadio($data->status,'status')}}
            </div>
        </div>
        <div class="layui-form-item" style="width: 90%; margin: 0px auto; background-color: #ffffff;">
            <div id="editor" style="margin: 50px 0 50px 0">
                <?php echo $data->content ?>
            </div>
            <textarea name="content" id="article-content" style="display: none;">{{$data->content}}</textarea>
        </div>
        <div class="layui-form-item layui-hide">
            @can('article.update')
            <input type="button" lay-submit lay-filter="admin-article-submit" id="admin-article-submit" value="确认">
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

            var $article = $("#article-content");
            editor.customConfig.onchange = function (html) {
                // 监控变化，同步更新到 textarea
                $article.val(html)
            };

            editor.create();

        })
    </script>
@endsection
