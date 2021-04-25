@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-index.css")}}">
@endsection

@section('content')
    <div class="banner">
        <div class="layui-carousel" id="slide">
            <div carousel-item>
                @if(!empty($banner))
                    @foreach($banner as $v)
                        <div><img src="{{$v['image']}}"></div>
                    @endforeach
                @endif
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

            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        layui.config({
            base: "{{asset('/assets/plugins/layui/')}}/" //静态资源所在路径
        }).use(['carousel'], function(){
            var $ = layui.$,
                carousel = layui.carousel;

            carousel.render({
                elem: '#slide',
                width: '100%',
                height: '410px',
                interval: 5000
            });
        })
    </script>
@endsection


