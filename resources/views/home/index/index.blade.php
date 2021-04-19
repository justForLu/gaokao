@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-index.css")}}">
@endsection

@section('content')
    <div class="banner">
        <div class="slide">
            <ul class="pics">
                @if($banner_arr)
                    @foreach($banner_arr as $v)
                        <li style="background:url({{$v['image']}}) center top no-repeat;">
                            <a href="{{$v['link']}}" target="_blank" class="slide_a" title="{{$v['alt']}}"></a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="section slide_nav">
                <div class="num">
                    @if($banner_arr)
                        @foreach($banner_arr as $v)
                            <span></span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="tjyj-box">
            <div class="tjgb">
                <div>
                    <img src="{{asset('/assets/home/images/laba.png')}}">
                    <div class="new-ruzhi">
                        <p>最新消息：李XX成功入职郑州富士康</p>
                        <p>最新消息：长XX成功入职郑州富士康</p>
                        <p>最新消息：找XX成功入职郑州富士康</p>
                        <p>最新消息：王XX成功入职郑州富士康</p>
                        <p>最新消息：李XX成功入职郑州富士康</p>
                        <p>最新消息：长XX成功入职郑州富士康</p>
                        <p>最新消息：找XX成功入职郑州富士康</p>
                        <p>最新消息：王XX成功入职郑州富士康</p>
                        <p>最新消息：李XX成功入职郑州富士康</p>
                        <p>最新消息：长XX成功入职郑州富士康</p>
                        <p>最新消息：王XX成功入职郑州富士康</p>
                    </div>
                </div>
            </div>
            <div class="tjyj">
                <div class="title">
                    <img src="{{asset('/assets/home/images/index-rebate.png')}}">
                    <h1>推荐有奖</h1>
                </div>
                <div class="jl-box">
                    <!--入职奖励-->
                    <div class="rzjl">
                        <div>
                            <p>159****5874报名成功获得奖金1000元</p>
                            <p>152****5874报名成功获得奖金1000元</p>
                            <p>151****5874报名成功获得奖金1000元</p>
                            <p>156****5874报名成功获得奖金1000元</p>
                            <p>158****5874报名成功获得奖金1000元</p>
                            <p>159****5874报名成功获得奖金1000元</p>
                            <p>152****5874报名成功获得奖金1000元</p>
                            <p>151****5874报名成功获得奖金1000元</p>
                            <p>156****5874报名成功获得奖金1000元</p>
                            <p>158****5874报名成功获得奖金1000元</p>
                            <p>151****5874报名成功获得奖金1000元</p>
                        </div>
                    </div>
                    <!--推荐奖励-->
                    <div class="yqjl">
                        <div>
                            <p>131****9845入职成功获得奖金1000元</p>
                            <p>132****9845入职成功获得奖金1000元</p>
                            <p>133****9845入职成功获得奖金1000元</p>
                            <p>134****9845入职成功获得奖金1000元</p>
                            <p>135****9845入职成功获得奖金1000元</p>
                            <p>131****9845入职成功获得奖金1000元</p>
                            <p>132****9845入职成功获得奖金1000元</p>
                            <p>133****9845入职成功获得奖金1000元</p>
                            <p>134****9845入职成功获得奖金1000元</p>
                            <p>135****9845入职成功获得奖金1000元</p>
                            <p>134****9845入职成功获得奖金1000元</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ii_list">
            <div class="ii_head">
                <div class="ii_key">
                    <a href="{{url("/home/recruit/index.html")}}" target="_blank">更多招聘 ></a>
                </div>
                <h2 class="ii_tit"><em>今日</em>热招</h2>
            </div>
            <ul class="ii_cont">
                @if($recruit_arr)
                    @foreach($recruit_arr as $v)
                        <li>
                            <div class="ii_pic">
                                <a href="{{url("/home/recruit/detail/".$v['id'].".html")}}" target="_blank">
                                    <img src="{{$v['factory']['image']}}" alt="{{$v['title']}}"/>
                                    <i class="ic_hot ic_ind_fachot"></i>
                                </a>
                            </div>
                            <div class="ii_con">
                                <a href="{{url("/home/recruit/detail/".$v['id'].".html")}}" target="_blank" class="name">{{$v['title']}}</a>
                                <p>招聘岗位：{{$v['posts']}}<br>
                                    招聘要求：@if($v['edu_ask']) {{$v['edu_ask']}}<i class="line"></i> @endif
                                    @if($v['sex_ask']) {{$v['sex_ask']}}<i class="line"></i> @endif
                                    @if($v['age_ask']) {{$v['age_ask']}} @endif <br>
                                    综合薪资：<span class="price">{{$v['salary_up']}}-{{$v['salary_down']}}元/月</span>
                                </p>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="ptjz">
            <div class="ptjz-title">
                <h2>
                    <p>平台价值</p>
                    <span></span>
                </h2>
            </div>
            <div class="jz-box">
                <div class="jz-item">
                    <div class="jz-dsc">
                        <h4>热门岗位</h4>
                        <p>知名企业rennin挑选，100%靠谱工作等你入职</p>
                    </div>
                </div>
                <div class="jz-item">
                    <div class="jz-dsc">
                        <h4>热门岗位</h4>
                        <p>知名企业rennin挑选，100%靠谱工作等你入职</p>
                    </div>
                </div>
                <div class="jz-item">
                    <div class="jz-dsc">
                        <h4>热门岗位</h4>
                        <p>知名企业rennin挑选，100%靠谱工作等你入职</p>
                    </div>
                </div>
                <div class="jz-item">
                    <div class="jz-dsc">
                        <h4>热门岗位</h4>
                        <p>知名企业rennin挑选，100%靠谱工作等你入职</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="ii_list ii_store">
            <div class="ii_head">
                <div class="ii_key">
                    <a href="{{url("/home/shop/index.html")}}" target="_blank">更多门店 ></a>
                </div>
                <h2 class="ii_tit">
                    <em>门店</em>服务
                </h2>
            </div>
            <ul class="ii_cont">
                @if($shop_arr)
                    @foreach($shop_arr as $v)
                        <li class="t5">
                            <a href="{{url("/home/shop/detail/".$v['id'].".html")}}" target="_blank">
                                <div class="ii_pic">
                                    <img src="{{$v['image']}}" alt="{{$v['name']}}"/>
                                </div>
                                <div class="ii_con">
                                    <span class="name">{{$v['name']}}</span>
                                    <p>
                                        <i class="ic ic_ind_address"></i>{{$v['address']}}
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

    <div style="height:0;overflow:hidden;">
        <div class="layer_join layers" id="layer_join">
            <p style="text-align: right">
                <span class="close_join" style="padding: 8px; color: #999;">关闭</span>
            </p>
            <p class="to_login">
                <a href="{{url("/home/login")}}">我是会员>></a>
            </p>
            <form action="{{url("/home/enroll/sign_up")}}" class="form form-horizontal J_ajaxForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="type" value="2" />
                <input type="hidden" name="user_id" value="{{$user_id}}" />
                <div class="form-item">
                    <label class="form-label" for="">姓名：</label>
                    <div class="form-field">
                        <input type="text" placeholder="请输入您的姓名" style="width: 60%;border: 1px solid #dadbdf;padding: 4px;height: 35px;line-height: 35px;border-radius: 4px;" name="name">
                    </div>
                </div>
                <div class="form-item">
                    <label class="form-label" for="">电话：</label>
                    <div class="form-field">
                        <input type="text" placeholder="请输入您的联系电话" style="width: 60%;border: 1px solid #dadbdf;padding: 4px;height: 35px;line-height: 35px;border-radius: 4px;"  name="mobile">
                    </div>
                </div>

                <div class="form-item">
                    <button type="submit" class="btn btn_b btn_orange J_ajax_submit_btn">确定</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset("/assets/home/js/index.js")}}"></script>
    <script src="{{asset("/assets/home/js/tvp.player_v2_jq.js")}}"></script>
    <script type="text/javascript">
        $("#btn_join_layer").click(function () {
            $("#layer_join").css({"position": "fixed","bottom": "400px","left": "30%","background-color": "#fff","z-index": 99999999,"width": "40%","display":"block"});
        });
        $(".close_join").click(function () {
            $("#layer_join").hide();
            $(".layui-layer-shade").removeAttr("style");
            $(".layui-layer-shade").removeAttr("class");
        });
    </script>
@endsection


