@extends('admin.layout.eject')

@section('content')
    <div class="layui-form" lay-filter="layuiadmin-form-user" id="layuiadmin-form-user" style="padding: 20px 30px 0 0;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>账号</label>
            <div class="layui-input-block">
                <input type="text" name="username" lay-verify="required" placeholder="请输入账号" autocomplete="off" class="layui-input">
                <div class="layui-form-mid layui-word-aux">尽量填写手机号</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>密码</label>
            <div class="layui-input-block">
                <input type="text" name="password" lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>会员等级</label>
            <div class="layui-input-block">
                <select name="grade">
                    <option value="0">普通会员</option>
                    @if($grade)
                        @foreach($grade as $v)
                            <option value="{{$v['id']}}">{{$v['name']}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>手机号</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" placeholder="请输入手机号" value="" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>真实姓名</label>
            <div class="layui-input-block">
                <input type="text" name="real_name" placeholder="请输入真实姓名" value="" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required-star">*</span>昵称</label>
            <div class="layui-input-block">
                <input type="text" name="nickname" placeholder="请输入昵称" value="" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否机器人</label>
            <div class="layui-input-block">
                {{\App\Enums\BoolEnum::enumRadio(\App\Enums\BoolEnum::NO,'is_robot')}}
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            @can('user.store')
            <input type="button" lay-submit lay-filter="admin-user-submit" id="admin-user-submit" value="确认">
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
        }).use(['index', 'form', 'upload'], function(){
            var $ = layui.$,
                form = layui.form;

        })
    </script>
@endsection
