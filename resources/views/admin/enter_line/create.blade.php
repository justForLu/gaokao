@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-enter_line" id="layuiadmin-form-enter_line" style="padding: 20px 30px 0 0;">
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
            <label class="layui-form-label"><span class="required-star">*</span>省份</label>
            <div class="layui-input-block">
                @if(!empty($province))
                    <select name="province" lay-verify="required" lay-search="">
                        @foreach($province as $v)
                            <option value="{{$v['id']}}">{{$v['title']}}</option>
                        @endforeach
                    </select>
                    <div class="layui-form-mid layui-word-aux">高校在该身份的录取分数线</div>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>年份</label>
            <div class="layui-input-inline">
                <input type="text" name="year" class="layui-input" lay-verify="required" id="enter_line-laydate-year" autocomplete="off" placeholder="yyyy">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>文理科</label>
            <div class="layui-input-inline">
                {{\App\Enums\ScienceEnum::enumRadio(\App\Enums\ScienceEnum::SCIENCE,'science')}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>批次</label>
            <div class="layui-input-inline">
                {{\App\Enums\BatchEnum::enumSelect(\App\Enums\BatchEnum::ZERO,false,'batch')}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>最低分</label>
            <div class="layui-input-block">
                <input type="text" name="min_score" lay-verify="required|number" placeholder="请输入最低分" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>最低分位次</label>
            <div class="layui-input-block">
                <input type="text" name="min_rank" lay-verify="required|number" placeholder="请输入最低分位次" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>省控线</label>
            <div class="layui-input-block">
                <input type="text" name="control_line" lay-verify="required|number" placeholder="请输入省控线" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">该省份的省控线</div>
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('enter_line.store')
            <input type="button" lay-submit lay-filter="admin-enter_line-submit" id="admin-enter_line-submit" value="确认">
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
                elem: '#enter_line-laydate-year',
                type: 'year'
            });
        })
    </script>
@endsection
