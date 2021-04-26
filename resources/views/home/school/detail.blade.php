@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-fac.css")}}">
@endsection

@section('content')
    <div class="fac_wrap wrap">
        <div class="section">
            <div class="fac_top clearfix">
                <div class="fac_imgshow fac_imgswiper" id="fac_imgshow">
                    <div class="view">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="fac_top_info sto_top_info">
                    <p class="names">{{$data->name}}</p>

                    <p class="des">

                    </p>

                    <p class="sto_top_note">郑重声明：本站所有发布招聘信息都经过工立方官方核实，100%真实有效，请大家放心报名！</p>
                </div>
            </div>
        </div>

        <div class="fac_main section clearfix">
            <div class="s_l">
                <div class="sto_main">
                    <div class="fac_list_tab">
                        <ul id="sto_contab">
                            <li class="active"><a href="javascript:select_item(0);">学校概况</a></li>
                            <li><a href="javascript:select_item(1);">开设专业</a></li>
                            <li><a href="javascript:select_item(2);">录取分数</a></li>
                        </ul>
                        <span class="nums"></span>
                    </div>
                    <div class="sto_main_con" style="display: block;">
                        <ul class="fac_list">

                        </ul>
                    </div>
                    <div class="sto_main_con" style="display: none;">
                        <ul class="store_local">
                            <li class="fac_queitem ic_type type2">开设专业</li>
                        </ul>
                    </div>
                    <div class="sto_main_con">
                        <ul class="store_adv_list clearfix">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="s_r">

                <div class="fac_info fac_rlist">
                    <div class="ner_title">
                        <i class="ic_line ic_ner_t"></i>
                        <h3>热门高校</h3>
                    </div>
                    <div class="fac_maps">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset("/assets/home/js/fac.js")}}"></script>
    <script src="{{asset("/assets/home/js/push.js")}}"></script>
    <script>
        var select_item = function (i) {
            $("#sto_contab li").removeClass();
            $("#sto_contab li").eq(i).addClass("active");
            $(".sto_main_con").css("display","none");
            $(".sto_main_con").eq(i).css("display","block");
        };
    </script>
@endsection


