@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-feedback" id="layuiadmin-form-feedback" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->name}}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->mobile}}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Email</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->email}}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">反馈内容</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->content}}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                {{\App\Enums\FeedbackEnum::enumRadio($data->status,'status')}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="请输入备注" class="layui-textarea">{{$data->remark}}</textarea>
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('feedback.update')
            <input type="button" lay-submit lay-filter="admin-feedback-submit" id="admin-feedback-submit" value="确认">
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
        }).use(['index', 'form'], function(){
            var $ = layui.$,
                form = layui.form;

        })
    </script>
@endsection
