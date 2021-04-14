@extends('admin.layout.eject')

@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <style type="text/css">
                    #look-user-info label.layui-form-label {
                        color: #666;
                        width: 120px;
                    }
                    #look-user-info div.layui-word-aux {

                    }
                    #look-user-info div.head-img {
                        width: 65px;
                        height: 65px;
                        border: 1px #ececec solid;
                        padding: 4px;
                    }
                    #look-user-info div.head-img img{
                        width: 64px;
                        height: 64px;
                    }
                </style>
                <div class="layui-card-body">
                    <div class="layui-form" id="look-user-info" style="padding: 20px 30px 0 0;">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">用户名：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->username}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">头像：</label>
                                <div class="layui-input-inline">
                                    <div class="head-img"><img src="{{$data->head_img}}" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">真实姓名：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->real_name}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">昵称：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->nickname}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">省市县：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->province_city}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">详细地址：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->address}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">手机号：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->mobile}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">邮箱：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->email}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">汽油余额：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->gasoline_money}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">柴油余额：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->diesel_money}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">邀请码：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->invite_code}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">登录次数：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->login_times}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">上次登录时间：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->login_time}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">上次登录IP：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->login_ip}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">支付宝账号：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->alipay}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">支付宝账号昵称：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->alipay_name}}</div>
                                </div>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">银行名称：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->bank_name}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">开户行支行：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->bank_branch}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">持卡人姓名：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->account_name}}</div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">卡号：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->account_no}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">注册时间：</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">{{$data->create_time}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
