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
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/teacher1.jpeg')}}" alt="师资力量">
                </div>
                <div class="item-body">
                    <div class="teacher-name">韩超</div>
                    <div class="teacher-desc">
                        韩超老师毕业于北京师范大学，在校期间荣获多次国家奖学金，实力强大。并且韩超老师为人热情，乐于助人，对待学生非常用心。
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/teacher1.jpeg')}}" alt="师资力量">
                </div>
                <div class="item-body">
                    <div class="teacher-name">韩超</div>
                    <div class="teacher-desc">
                        韩超老师毕业于北京师范大学，在校期间荣获多次国家奖学金，实力强大。并且韩超老师为人热情，乐于助人，对待学生非常用心。
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/teacher1.jpeg')}}" alt="师资力量">
                </div>
                <div class="item-body">
                    <div class="teacher-name">韩超</div>
                    <div class="teacher-desc">
                        韩超老师毕业于北京师范大学，在校期间荣获多次国家奖学金，实力强大。并且韩超老师为人热情，乐于助人，对待学生非常用心。
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/teacher1.jpeg')}}" alt="师资力量">
                </div>
                <div class="item-body">
                    <div class="teacher-name">韩超</div>
                    <div class="teacher-desc">
                        韩超老师毕业于北京师范大学，在校期间荣获多次国家奖学金，实力强大。并且韩超老师为人热情，乐于助人，对待学生非常用心。
                    </div>
                </div>
            </div>
        </div>
        <div class="ii_list">
            <div class="ii_head">
                <h2 class="ii_tit">
                    <em>中夏</em>掠影
                </h2>
            </div>
        </div>
        <div class="zxly">
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/team1.jpeg')}}" alt="中夏掠影">
                </div>
            </div>
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/team2.jpeg')}}" alt="中夏掠影">
                </div>
            </div>
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/team3.jpeg')}}" alt="中夏掠影">
                </div>
            </div>
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/team4.jpeg')}}" alt="中夏掠影">
                </div>
            </div>
            <div class="item">
                <div class="item-img">
                    <img src="{{asset('/assets/home/images/team5.jpeg')}}" alt="中夏掠影">
                </div>
            </div>
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


