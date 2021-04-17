@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-score" id="layuiadmin-form-score" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>省份</label>
            <div class="layui-input-block">
                @if(!empty($province))
                    <select name="province">
                        @foreach($province as $v)
                            <option value="{{$v['id']}}" @if($v['id'] == $data->province) selected @endif>{{$v['title']}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>年份</label>
            <div class="layui-input-inline">
                <input type="text" name="year" value="{{$data->year}}" class="layui-input" lay-verify="required" autocomplete="off" id="score-laydate-year" placeholder="yyyy">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>理科一本线</label>
            <div class="layui-input-block">
                <input type="text" name="yiben_li" value="{{$data->yiben_li}}" placeholder="请输入理科一本线" lay-verify="required|number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>理科二本线</label>
            <div class="layui-input-block">
                <input type="text" name="erben_li" value="{{$data->erben_li}}" placeholder="请输入理科二本线" lay-verify="required|number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>理科三本线</label>
            <div class="layui-input-block">
                <input type="text" name="sanben_li" value="{{$data->sanben_li}}" placeholder="请输入理科三本线" lay-verify="required|number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>理科大专线</label>
            <div class="layui-input-block">
                <input type="text" name="dazhuan_li" value="{{$data->dazhuan_li}}" placeholder="请输入理科大专线" lay-verify="required|number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>文科一本线</label>
            <div class="layui-input-block">
                <input type="text" name="yiben_wen" value="{{$data->yiben_wen}}" placeholder="请输入文科一本线" lay-verify="required|number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>文科二本线</label>
            <div class="layui-input-block">
                <input type="text" name="erben_wen" value="{{$data->erben_wen}}" placeholder="请输入文科二本线" lay-verify="required|number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>文科三本线</label>
            <div class="layui-input-block">
                <input type="text" name="sanben_wen" value="{{$data->sanben_wen}}" placeholder="请输入文科三本线" lay-verify="required|number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>文科大专线</label>
            <div class="layui-input-block">
                <input type="text" name="dazhuan_wen" value="{{$data->dazhuan_wen}}" placeholder="请输入文科大专线" lay-verify="required|number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('score.update')
            <input type="button" lay-submit lay-filter="admin-score-submit" id="admin-score-submit" value="确认">
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
                elem: '#score-laydate-year',
                type: 'year'
            });
        })
    </script>
@endsection
