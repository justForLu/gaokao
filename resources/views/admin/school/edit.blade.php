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
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
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
        }).use(['index', 'form','upload'], function(){
            var $ = layui.$,
                form = layui.form,
                upload = layui.upload;

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
        })
    </script>
@endsection
