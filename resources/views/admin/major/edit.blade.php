@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-major" id="layuiadmin-form-major" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>高校</label>
            <div class="layui-input-block">
                @if(!empty($school))
                    <select name="school_id" lay-verify="required" lay-search="">
                        @foreach($school as $v)
                            <option value="{{$v['id']}}" @if($v['id'] == $data->school_id) selected @endif>{{$v['name']}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>文理科</label>
            <div class="layui-input-inline">
                {{\App\Enums\ScienceEnum::enumRadio($data->science,'science')}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>批次</label>
            <div class="layui-input-inline">
                {{\App\Enums\BatchEnum::enumSelect($data->batch,false,'batch')}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>最低分</label>
            <div class="layui-input-block">
                <input type="text" name="min_score" value="{{$data->min_score}}" lay-verify="required|number" placeholder="请输入最低分" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>最低分位次</label>
            <div class="layui-input-block">
                <input type="text" name="min_rank" value="{{$data->min_rank}}" lay-verify="required|number" placeholder="请输入最低分位次" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>省控线</label>
            <div class="layui-input-block">
                <input type="text" name="control_line" value="{{$data->control_line}}" lay-verify="required|number" placeholder="请输入省控线" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">该省份的省控线</div>
            </div>
        </div>




        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>高校</label>
            <div class="layui-input-block">
                @if(!empty($school))
                    <select name="school_id" lay-verify="required" lay-search="">
                        @foreach($school as $v)
                            <option value="{{$v['id']}}" @if($v['id'] == $data->school_id) selected @endif>{{$v['name']}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>专业分类</label>
            <div class="layui-input-block">
                @if(!empty($category))
                    <select name="school_id" lay-verify="required" lay-search="">
                        @foreach($category as $v)
                            <option value="{{$v['id']}}" @if($v['id'] == $data->category_id) selected @endif>{{$v['name']}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>专业名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" value="{{$data->name}}" lay-verify="required" placeholder="请输入专业名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">专业等级</label>
            <div class="layui-input-inline">
                {{\App\Enums\MajorTypeEnum::enumSelect($data->type,'请选择专业类别','type')}}
                <div class="layui-form-mid layui-word-aux">普通专业可不选</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">学科等级</label>
            <div class="layui-input-inline">
                {{\App\Enums\MajorEnum::enumSelect($data->grade,'请选择学科等级','grade')}}
                <div class="layui-form-mid layui-word-aux">普通专业可不选</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>学制(年)</label>
            <div class="layui-input-block">
                <input type="text" name="edu_system" value="{{$data->edu_system}}" lay-verify="required|number" placeholder="请输入学制(年)" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">请填写数字</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="text" name="sort" placeholder="请输入排序" value="{{$data->sort}}" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">排序越大越靠前</div>
            </div>
        </div>
        <div class="layui-form-item" style="width: 90%; margin: 0px auto; background-color: #ffffff;">
            <div id="editor" style="margin: 50px 0 50px 0">
                <?php echo $data->content ?>
            </div>
            <textarea name="content" id="major-content" style="display: none;">{{$data->content}}</textarea>
        </div>
        <div class="layui-form-item layui-hide">
            @can('major.update')
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

            var $article = $("#major-content");
            editor.customConfig.onchange = function (html) {
                // 监控变化，同步更新到 textarea
                $article.val(html)
            };

            editor.create();
        })
    </script>
@endsection
