@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-major_line" id="layuiadmin-form-major_line" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>高校</label>
            <div class="layui-input-inline">
                @if(!empty($school))
                    <select name="school_id" lay-verify="required" lay-filter="school" lay-search="">
                        @foreach($school as $v)
                            <option value="{{$v['id']}}" @if($v['id'] == $data->school_id) selected @endif>{{$v['name']}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="layui-inline">
                <select name="major_id" id="major" lay-verify="required">
                    <option value="">请选择专业</option>
                    @if($major)
                        @foreach($major as $v)
                            <option value="{{$v['id']}}" @if($v['id'] == $data->major_id) selected @endif>{{$v['name']}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>省份</label>
            <div class="layui-input-block">
                @if(!empty($province))
                    <select name="province" lay-verify="required" lay-search="">
                        @foreach($province as $v)
                            <option value="{{$v['id']}}" @if($v['id'] == $data->province) selected @endif>{{$v['title']}}</option>
                        @endforeach
                    </select>
                    <div class="layui-form-mid layui-word-aux">高校在该身份的录取分数线</div>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>年份</label>
            <div class="layui-input-inline">
                <input type="text" name="year" value="{{$data->year}}" class="layui-input" lay-verify="required" id="major_line-laydate-year" autocomplete="off" placeholder="yyyy">
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
            <label class="layui-form-label">最高分</label>
            <div class="layui-input-block">
                <input type="text" name="max_score" value="{{$data->max_score}}" lay-verify="required|number" placeholder="请输入最高分" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">平均分</label>
            <div class="layui-input-block">
                <input type="text" name="avg_score" value="{{$data->avg_score}}" lay-verify="required|number" placeholder="请输入平均分" autocomplete="off" class="layui-input">
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
            <label class="layui-form-label"><span class="required-star">*</span>招生人数</label>
            <div class="layui-input-block">
                <input type="text" name="recruit_num" value="{{$data->recruit_num}}" lay-verify="required|number" placeholder="请输入省控线" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">报考人数</label>
            <div class="layui-input-block">
                <input type="text" name="sign_num" value="{{$data->sign_num}}" lay-verify="required|number" placeholder="请输入省控线" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">录取人数</label>
            <div class="layui-input-block">
                <input type="text" name="enter_num" value="{{$data->enter_num}}" lay-verify="required|number" placeholder="请输入省控线" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('major_line.update')
            <input type="button" lay-submit lay-filter="admin-major_line-submit" id="admin-major_line-submit" value="确认">
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
        }).use(['index', 'form','laydate'], function(){
            var $ = layui.$,
                form = layui.form,
                laydate = layui.laydate;

            //年选择器
            laydate.render({
                elem: '#major_line-laydate-year',
                type: 'year'
            });

            //高校专业联动
            var school_id = 0;
            form.on('select(school)', function (obj) {
                var school = obj.value;
                //下拉框值发生改变时
                if(school !== school_id){
                    school_id = school;
                    $.ajax({
                        url:'/admin/get_major_list',
                        method:'get',
                        data: {school_id:school_id},
                        success: function (res) {
                            //城市下拉框更新
                            var html_major = "<option value=''>请选择城市</option>";
                            if(res.data){
                                $.each(res.data, function (i,j) {
                                    html_major += "<option value='"+j.id+"'>" + j.title + "</option>";
                                });
                            }
                            $("#major").html(html_major);
                            form.render();
                        },
                        fail: function () {
                            //城市下拉框清空
                            var html_major = "<option value=''>请选择城市</option>";
                            $("#major").html(html_major);
                            form.render();
                        }
                    });
                }
            });
        })
    </script>
@endsection
