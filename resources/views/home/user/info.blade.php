@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/user.css")}}">
@endsection

@section('content')
    <div class="user_top wrap">
        <div class="section">
            <div class="user_top_avt">
                <div class="imgs">
                    <img src="@if($userInfo->image) {{$userInfo->image}}  @else {{asset("/assets/home/images/default_user_img.png")}}  @endif" alt="">
                    <a href="" class="to_avt">
                        <i class="ic ic_avt_ico"></i>
                    </a>
                </div>
                <p>欢迎你，亲爱的 <em>{{$userInfo->nickname}}</em>！</p>
            </div>
            <div class="user_top_num">
                <a href="{{url("/home/user/message.html")}}" class="nums">
                    消息<em>0</em>
                </a>
                <a href="{{url("/home/user/collect_recruit.html")}}" class="nums">
                    我的收藏<em>{{$count_collect}}</em>
                </a>
            </div>
        </div>
    </div>

    <div class="section user_main">
        <div class="s_l">
            <div class="user_left c_border">
                <div class="user_left c_border">
                    <ul class="user_left_nav">
                        <li @if($menu == 'message') class="active" @endif><a href="{{url("/home/user/message.html")}}"><i class="ic iconfont">&#xe652;</i>消息中心</a></li>
                        <li @if($menu == 'info') class="active" @endif><a href="{{url("/home/user/info.html")}}"><i class="ic iconfont">&#xe650;</i>我的资料</a></li>
{{--                        <li @if($menu == 'cash_out') class="active" @endif><a href="{{url("/home/user/cash_out.html")}}"><i class="ic iconfont">&#xe669;</i>提现资料</a></li>--}}
                        <li @if($menu == 'collect') class="active" @endif><a href="{{url("/home/user/collect_recruit.html")}}"><i class="ic iconfont">&#xe674;</i>我的收藏</a></li>
                        <li @if($menu == 'portrait') class="active" @endif><a href="{{url("/home/user/portrait.html")}}"><i class="ic iconfont">&#xe651;</i>头像设置</a></li>
                        <li @if($menu == 'account') class="active" @endif><a href="{{url("/home/user/account.html")}}"><i class="ic iconfont">&#xe63c;</i>帐号管理</a></li>
                    </ul>
                    <div class="user_lapp">
                        <img src="{{asset("/assets/home/images/weixin.jpg")}}" alt="">
                        扫描二维码，即可订阅岗位，<br>数万岗位随手可得
                    </div>
                </div>
            </div>
        </div>
        <div class="s_r">
            <div class="uc_title">
                <i class="line"></i>
                <h3>我的资料</h3>
                <span class="more">完善资料，让我们更加了解你</span>
            </div>
            <div class="c_border">
                <form action="{{url("/home/user/sub_info")}}" method="post" id='form' class="form form-horizontal uc_form J_ajaxForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-item t_org">
                        <label class="form-label" for=""></label>
                        <div class="form-field">提示：资料请谨慎填写！</div>
                    </div>
                    <div class="form-item">
                        <label class="form-label" for="">手机号：</label>
                        <div class="form-field">
                            <input type="text" class="form-text" name="mobile" value="{{$userInfo->mobile}}">
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label" for="">姓名：</label>
                        <div class="form-field">
                            <input type="text" name="realname" class="form-text" placeholder="请填写真实姓名" value="{{$userInfo->realname}}">
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label" for="">身份证号：</label>
                        <div class="form-field">
                            <input type="text" name="id_card"  class="form-text" placeholder="请填写您的身份证号" value="{{$userInfo->id_card}}"/>
                            <span style="color:#f00;font-size:12px;width:120px;" id="card_num_tip"></span>
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label" for="">性别：</label>
                        <div class="form-field form-field-rc">
                            <label class="form-label-rc"><input name="sex" value="1" @if($userInfo->sex == 1) checked @endif type="radio" class="form-radio"/><span>男</span></label>
                            <label class="form-label-rc"><input name="sex" value="2" @if($userInfo->sex == 2) checked @endif type="radio" class="form-radio"/><span>女</span></label>
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label" for="">年龄：</label>
                        <div class="form-field">
                            <select name="year" class="hj_select form-select inline-ele">
                                @if($year)
                                    @foreach($year as $v)
                                        <option @if($v == $birth_year) selected @endif value="{{$v}}" >{{$v}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <select name="month" class="hj_select form-select inline-ele">
                                @if($month)
                                    @foreach($month as $v)
                                        <option @if($v == $birth_month) selected @endif value="{{$v}}" >{{$v}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label" for="">户籍：</label>
                        <div class="form-field">
                            <select name="province" data-url="{{url('/home/get_city_list')}}" data-init="true"
                                    data-value="{{isset($city['province']) ? $city['province'] : ''}}" data-target="subCity" data-val="city_id" class="hj_select form-select inline-ele J_ajax_select">
                                <option>请选择</option>
                            </select>
                            <select name="city" data-val="city" id="subCity" data-url="{{url('/home/get_city_list')}}"
                                    data-value="{{isset($city['city']) ? $city['city'] : ''}}"  data-target="subDistinct"  class="hj_select form-select inline-ele J_ajax_select">
                                <option>请选择</option>
                            </select>
                            <select name="area" data-val="city_id" id="subDistinct" data-url="{{url('/home/get_city_list')}}"
                                    data-value="{{isset($city['area']) ? $city['area'] : ''}}" data-target="thrCity" class="hj_select form-select inline-ele J_ajax_select">
                                <option>请选择</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-action  action-left">
                        <button type="submit" class="btn btn_b btn_orange btn_round J_ajax_submit_btn">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="{{asset("/assets/home/js/user.js")}}"></script>
@endsection


