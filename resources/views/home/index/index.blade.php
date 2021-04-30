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
                        <p>最新消息：李XX刚刚咨询过</p>
                        <p>最新消息：王XX刚刚咨询过</p>
                        <p>最新消息：赵XX刚刚咨询过</p>
                        <p>最新消息：钱XX刚刚咨询过</p>
                        <p>最新消息：孙XX刚刚咨询过</p>
                        <p>最新消息：周XX刚刚咨询过</p>
                        <p>最新消息：吴XX刚刚咨询过</p>
                        <p>最新消息：郑XX刚刚咨询过</p>
                        <p>最新消息：张XX刚刚咨询过</p>
                        <p>最新消息：武XX刚刚咨询过</p>
                        <p>最新消息：李XX刚刚咨询过</p>
                    </div>
                </div>
            </div>
            <div class="tjyj">
                <div class="title">
                    <img src="{{asset('/assets/home/images/index-rebate.png')}}">
                    <h1>欢迎咨询</h1>
                </div>
                <div class="jl-box">
                    <!--入职奖励-->
                    <div class="rzjl">
                        <div>
                            <p>159****5874刚刚咨询过</p>
                            <p>152****5874刚刚咨询过</p>
                            <p>151****5874刚刚咨询过</p>
                            <p>156****5874刚刚咨询过</p>
                            <p>158****5874刚刚咨询过</p>
                            <p>159****5874刚刚咨询过</p>
                            <p>152****5874刚刚咨询过</p>
                            <p>151****5874刚刚咨询过</p>
                            <p>156****5874刚刚咨询过</p>
                            <p>158****5874刚刚咨询过</p>
                            <p>151****5874刚刚咨询过</p>
                        </div>
                    </div>
                    <!--推荐奖励-->
                    <div class="yqjl">
                        <div>
                            <p>159****5874刚刚咨询过</p>
                            <p>152****5874刚刚咨询过</p>
                            <p>151****5874刚刚咨询过</p>
                            <p>156****5874刚刚咨询过</p>
                            <p>158****5874刚刚咨询过</p>
                            <p>159****5874刚刚咨询过</p>
                            <p>152****5874刚刚咨询过</p>
                            <p>151****5874刚刚咨询过</p>
                            <p>156****5874刚刚咨询过</p>
                            <p>158****5874刚刚咨询过</p>
                            <p>151****5874刚刚咨询过</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ii_list">
            <div class="ii_head">
                <h2 class="ii_tit"><em>师资</em>力量</h2>
            </div>
        </div>
        <div class="ptjz">

        </div>
        <div class="ii_list ii_store">
            <div class="ii_head">
                <h2 class="ii_tit">
                    <em>中夏</em>掠影
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


