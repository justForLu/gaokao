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


    <style>
        .av_btn{width:100%; display:block; background:#f8f8f8; line-height:40px;border-radius:50px; text-align:center;cursor:pointer;}
        .av_btn:hover{ background:#ddd; color:#000}
        label {position: relative;display: inline-block;background: #D0EEFF;border: 1px solid #99D3F5;border-radius: 4px;padding: 4px 12px;overflow: hidden;color: #1E88C7;text-decoration: none;text-indent: 0;line-height: 20px;cursor: pointer;}
        /*隐藏默认样式*/
        input[id=file_image] {width: 82px;height: 30px;opacity: 0;position: relative;bottom: 40px;}
    </style>
    <div class="user_main">
        <div class="section">
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
            <div class="s_r user_avt">
                <div class="uc_title">
                    <i class="line"></i>
                    <h3>头像设置</h3>
                </div>
                <div class="c_border">
                    <form action="{{url("/home/user/sub_portrait")}}" method="post" id='form' class="J_ajaxForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="avt_sel">
                            <div class="avt_dis">
                                <img src="{{$image}}" alt="" class="portrait-cover cover">
                                <div class="J_upload_portrait" data-id="image" data-_token="{{ csrf_token() }}">
                                    @if(!empty($image))
                                        <input type="hidden" name="image_val" value="{{ $image }}">
                                        <input type="hidden" name="image_path[]" value="{{ $image }}">
                                    @endif
                                </div>
                            </div>
                            <p class="con">选一张您喜欢的照片做头像吧 ( 建议图片尺寸不小于200×200 ; 支持jpg、jpeg、gif、png、bmp格式的图片，大小不超过2M )。</p>
                        </div>
                        <div class="avt_show">
                            <div class="avt_f">
                                <img src="{{$image}}" alt="" class="portrait-cover cover">
                                <button type="submit"  id="saveImgBtn" class="btn_m btn_round btn btn_orange J_ajax_submit_btn">保存</button>
                                <a href="{{url("/home/user/portrait.html")}}" class="btn_m btn_round btn btn_ignore">取消</a>
                            </div>
                            <div class="con">
                                <p>头像预览</p>
                                <div class="avt_s s1">
                                <span class="img_vt">
                                    <img src="{{$image}}" alt="" class="portrait-cover cover">
                                </span>
                                    180*180像素
                                </div>
                                <div class="avt_s s2">
                                <span class="img_vt">
                                    <img src="{{$image}}" alt="" class="portrait-cover cover">
                                </span>
                                    100*100像素
                                </div>
                                <div class="avt_s s3">
                                <span class="img_vt">
                                    <img src="{{$image}}" alt="" class="portrait-cover cover">
                                </span>
                                    50*50像素
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset("/assets/home/js/user.js")}}"></script>
    <script src="{{asset("/assets/plugins/weui/lrz.min.js")}}"></script>
@endsection


