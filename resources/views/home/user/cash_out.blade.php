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

    <div class="user_main">
        <div class="section user_main">
            <div class="s_l">
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

            <div class="s_r">
                <div class="uc_title">
                    <i class="line"></i>
                    <h3>提现资料</h3>
                </div>
                <div class="c_border">
                    <p class="uc_title_msg">为了您奖金的顺利发放，请如实填写以下信息。</p>
                    <form action="" id="cash_form" class="form form-horizontal uc_form user_tk_info">
                        <div class="form-item">
                            <label class="form-label" for=""><i class="required">*</i>姓名：</label>
                            <div class="form-field">
                                <input type="text" placeholder="请填写您的真实姓名" class="form-text" name="f_name" value="">
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label" for=""><i class="required">*</i>卡号：</label>
                            <div class="form-field">
                                <input type="text" placeholder="请填写本人银行卡号" class="form-text" name="f_bank_card" value="">
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label" for=""><i class="required">*</i>开户行：</label>
                            <div class="form-field">
                                <input type="text" placeholder="请填写您的开户银行" class="form-text" name="f_kh_bank" value="">
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label" for=""><i class="required">*</i>开户地区：</label>
                            <div class="form-field">
                                <select class="form-select inline-ele region" name="lo_p" size="1" id="lo_p">
                                    <option value="">省</option>
                                    <option value="北京" data-id="110000">北京</option>
                                    <option value="天津" data-id="120000">天津</option>
                                    <option value="河北省" data-id="130000">河北省</option>
                                    <option value="山西省" data-id="140000">山西省</option>
                                    <option value="内蒙古自治区" data-id="150000">内蒙古自治区</option>
                                    <option value="辽宁省" data-id="210000">辽宁省</option>
                                    <option value="吉林省" data-id="220000">吉林省</option>
                                    <option value="黑龙江省" data-id="230000">黑龙江省</option>
                                    <option value="上海" data-id="310000">上海</option>
                                    <option value="江苏省" data-id="320000">江苏省</option>
                                    <option value="浙江省" data-id="330000">浙江省</option>
                                    <option value="安徽省" data-id="340000">安徽省</option>
                                    <option value="福建省" data-id="350000">福建省</option>
                                    <option value="江西省" data-id="360000">江西省</option>
                                    <option value="山东省" data-id="370000">山东省</option>
                                    <option value="河南省" data-id="410000">河南省</option>
                                    <option value="湖北省" data-id="420000">湖北省</option>
                                    <option value="湖南省" data-id="430000">湖南省</option>
                                    <option value="广东省" data-id="440000">广东省</option>
                                    <option value="广西" data-id="450000">广西</option>
                                    <option value="海南省" data-id="460000">海南省</option>
                                    <option value="重庆" data-id="500000">重庆</option>
                                    <option value="四川省" data-id="510000">四川省</option>
                                    <option value="贵州省" data-id="520000">贵州省</option>
                                    <option value="云南省" data-id="530000">云南省</option>
                                    <option value="西藏自治区" data-id="540000">西藏自治区</option>
                                    <option value="陕西省" data-id="610000">陕西省</option>
                                    <option value="甘肃省" data-id="620000">甘肃省</option>
                                    <option value="青海省" data-id="630000">青海省</option>
                                    <option value="宁夏回族自治区" data-id="640000">宁夏回族自治区</option>
                                    <option value="新疆维吾尔自治区" data-id="650000">新疆维吾尔自治区</option>
                                    <option value="台湾" data-id="710000">台湾</option>
                                    <option value="香港特别行政区" data-id="810000">香港特别行政区</option>
                                    <option value="澳门特别行政区" data-id="820000">澳门特别行政区</option>
                                </select>
                                <select class="form-select inline-ele region" name="lo_c" size="1" id="lo_c">
                                    <option value="">市</option>
                                </select>
                                <select class="form-select inline-ele region" name="lo_a" size="1" id="lo_a">
                                    <option value="">区/县</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-item form-item_card">
                            <label class="form-label" for=""><i class="required">*</i>身份证原件正面照片：</label>
                            <div class="form-field">
                                <p class="upload_note">上传的证件照需是清晰彩色照，支持jpg、png、gif图片格式，大小不超过2M。</p>
                                <div class="upload_card">
                                    <em class="titles">案例：</em>
                                    <img class="card_exp" width="196px" src="{{asset("/assets/home/images/card_up.jpg")}}" id="idcard_up_img">
                                    <div class="upload_card_con">
                                        <div id="idcard_up" class="webuploader-container">
                                            <div class="webuploader-pick">
                                                <div class="upbtn">点击上传</div>
                                            </div>
                                            <div id="rt_rt_1ef024to21hlq1u95da817n51bta1" style="position: absolute; top: 0px; left: 43px; width: 110px; height: 41px; overflow: hidden; bottom: auto; right: auto;">
                                                <input type="file" name="file" class="webuploader-element-invisible" multiple="multiple" accept="image/jpeg,image/bmp">
                                                <label style="opacity: 0; width: 100%; height: 100%; display: block; cursor: pointer; background: rgb(255, 255, 255);"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-item form-item_card">
                            <label class="form-label" for=""><i class="required">*</i>身份证原件反面照片：</label>
                            <div class="form-field">
                                <p class="upload_note">上传的证件照需是清晰彩色照，支持jpg、png、gif图片格式，大小不超过2M。</p>
                                <div class="upload_card">
                                    <em class="titles">案例：</em>
                                    <img class="card_exp" width="196px" src="{{asset("/assets/home/images/card_down.jpg")}}" id="idcard_down_img">
                                    <div class="upload_card_con">
                                        <div id="idcard_down" class="webuploader-container">
                                            <div class="webuploader-pick">
                                                <div class="upbtn">点击上传</div>
                                            </div>
                                            <div id="rt_rt_1ef024to43mp1s7k1gab116t1t604" style="position: absolute; top: 0px; left: 43px; width: 110px; height: 41px; overflow: hidden; bottom: auto; right: auto;">
                                                <input type="file" name="file" class="webuploader-element-invisible" multiple="multiple" accept="image/jpeg,image/bmp">
                                                <label style="opacity: 0; width: 100%; height: 100%; display: block; cursor: pointer; background: rgb(255, 255, 255);"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-action  action-left">
                            <a href="javascript:void(0);" class="btn btn_b btn_orange btn_round" id="save_tx">确认</a>
                        </div>
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="lo_p_code" value="">
                        <input type="hidden" name="lo_c_code" value="">
                        <input type="hidden" name="lo_a_code" value="">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="idcard_up_data" value="">
                        <input type="hidden" name="idcard_down_data" value="">
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div id="some_file_queue" style="display:none;"></div>
    <div id="some_file_queue2" style="display:none;"></div>

@endsection

@section('scripts')
    <script src="{{asset("/assets/home/js/user.js")}}"></script>
@endsection


