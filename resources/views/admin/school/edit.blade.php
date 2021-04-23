@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-school" id="layuiadmin-form-school" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>高校名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" value="{{$data->name}}" placeholder="请输入高校名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>行政区划</label>
            <div class="layui-input-block">
                <div class="layui-inline">
                    <select name="province" id="province" lay-filter="province">
                        <option value="">请选择省份</option>
                        @if($province)
                            @foreach($province as $k_p => $val_p)
                                <option value="{{$val_p['id']}}" @if($val_p['id'] == $data->province) selected @endif>{{$val_p['title']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="layui-inline">
                    <select name="city" id="city" lay-filter="city">
                        <option value="">请选择城市</option>
                        @if($city)
                            @foreach($city as $k_c => $val_c)
                                <option value="{{$val_c['id']}}" @if($val_c['id'] == $data->city) selected @endif>{{$val_c['title']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="layui-inline">
                    <select name="area" id="area">
                        <option value="">请选择县/区</option>
                        @if($area)
                            @foreach($area as $k_a => $val_a)
                                <option value="{{$val_a['id']}}" @if($val_a['id'] == $data->area) selected @endif>{{$val_a['title']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>详细地址</label>
            <div class="layui-input-block">
                <input type="text" name="address" value="{{$data->address}}" placeholder="请输入详细地址" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">官方网址</label>
            <div class="layui-input-block">
                <input type="text" name="website" value="{{$data->website}}" placeholder="请输入官方网址" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">多个邮箱网址号“，”隔开</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">官方电话</label>
            <div class="layui-input-block">
                <input type="text" name="phone" value="{{$data->phone}}" placeholder="请输入官方电话" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">多个电话用逗号“，”隔开</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">官方邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="email" value="{{$data->email}}" placeholder="请输入官方邮箱" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">多个邮箱用逗号“，”隔开</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">面积(亩)</label>
            <div class="layui-input-inline">
                <input type="text" name="measure" value="{{$data->measure}}" placeholder="请输入面积(亩)" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>隶属</label>
            <div class="layui-input-inline">
                <input type="text" name="belong" value="{{$data->long}}" lay-verify="required" placeholder="请输入隶属" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标签</label>
            <div class="layui-input-block">
                @if(!empty($tag))
                    @foreach($tag as $v)
                        <input type="checkbox" name="tag[]" value="{{$v['id']}}" @if($v['checked'] == 1) checked @endif lay-skin="primary" title="{{$v['name']}}" />
                    @endforeach
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="text" name="sort" value="{{$data->sort}}" placeholder="请输入排序" autocomplete="off" class="layui-input">
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
            <textarea name="content" id="school-content" style="display: none;">{{$data->content}}</textarea>
        </div>
        <div class="layui-form-item layui-hide">
            @can('school.update')
            <input type="button" lay-submit lay-filter="admin-school-submit" id="admin-school-submit" value="确认">
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
        }).use(['index', 'form','upload','wangEditor'], function(){
            var $ = layui.$,
                form = layui.form,
                upload = layui.upload,
                wangEditor = layui.wangEditor;

            //省市县多级联动
            var province_id = 0;
            form.on('select(province)', function (obj) {
                var province = obj.value;
                //下拉框值发生改变时
                if(province !== province_id){
                    province_id = province;
                    $.ajax({
                        url:'/admin/get_city_list',
                        method:'get',
                        data: {id:province_id},
                        success: function (res) {
                            //城市下拉框更新
                            var html_city = "<option value=''>请选择城市</option>";
                            if(res){
                                $.each(res, function (i,j) {
                                    html_city += "<option value='"+j.id+"'>" + j.title + "</option>";
                                });
                            }
                            $("#city").html(html_city);
                            //区县下拉框清空
                            var html_area = "<option value=''>请选择区/县</option>";
                            $("#area").html(html_area);
                            form.render();
                        },
                        fail: function () {
                            //城市下拉框清空
                            var html_city = "<option value=''>请选择城市</option>";
                            $("#city").html(html_city);
                            //区县下拉框清空
                            var html_area = "<option value=''>请选择区/县</option>";
                            $("#area").html(html_area);
                            form.render();
                        }
                    });
                }
            });
            var city_id = 0;
            form.on('select(city)', function (obj) {
                var city = obj.value;
                if(city !== city_id){
                    city_id = city;
                    $.ajax({
                        url:'/admin/get_city_list',
                        method:'get',
                        data: {id:city_id},
                        success: function (res) {
                            //区县下拉框更新
                            var html_area = "<option value=''>请选择区/县</option>";
                            if(res){
                                $.each(res, function (i,j) {
                                    html_area += "<option value='"+j.id+"'>" + j.title + "</option>";
                                });
                            }
                            $("#area").html(html_area);
                            form.render();
                        },
                        fail: function () {
                            //区县下拉框清空
                            var html_area = "<option value=''>请选择区/县</option>";
                            $("#area").html(html_area);
                            form.render();
                        }
                    });
                }
            });

            //高校简介
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

            var $article = $("#school-content");
            editor.customConfig.onchange = function (html) {
                // 监控变化，同步更新到 textarea
                $article.val(html)
            };

            editor.create();
        })
    </script>
@endsection
