@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-fac.css")}}">
@endsection

@section('content')
    <div class="fac_wrap wrap">
        <div class="section">
            <div class="school_top clearfix">
                <div class="school_logo_box">
                    <div class="school_log">
                        <img src="{{$data->logo}}">
                    </div>
                </div>
                <div class="school_dec_box">
                    <h1 class="names">{{$data->name}}<span style="font-size: 14px;padding-left: 50px;">隶属：{{$data->belong}}</span></h1>
                    <p class="province_city">省市：{{$data->region}}<span style="padding-left: 30px;">地址：{{$data->address}}</span></p>
                    <div class="school_tag">
                        @if(!empty($data->tag_arr))
                            @foreach($data->tag_arr as $val)
                                <span>{{$val}}</span>
                            @endforeach
                        @endif
                    </div>
                    <p class="school_website">官方网址：{{$data->website}}</p>
                    <p class="school_phone">官方电话：{{$data->phone}}</p>
                    <p class="school_email">官方邮箱：{{$data->email}}</p>
                </div>
            </div>
        </div>

        <div class="fac_main section clearfix">
            <div class="s_l">
                <div class="sto_main">
                    <div class="fac_list_tab">
                        <ul id="sto_contab">
                            <li @if($nav == 'desc') class="active" @endif><a href="{{url("/home/school/detail/".$data->id.".html?nav=desc")}}">学校简介</a></li>
                            <li @if($nav == 'major') class="active" @endif><a href="{{url("/home/school/detail/".$data->id.".html?nav=major")}}">开设专业</a></li>
                            <li @if($nav == 'line') class="active" @endif><a href="{{url("/home/school/detail/".$data->id.".html?nav=line")}}">录取分数</a></li>
                        </ul>
                        <span class="nums"></span>
                    </div>
                    <div class="school_main_con" @if($nav == 'desc') style="display: block;" @else style="display: none;" @endif >
                        <div class="school-content">
                            <?php echo $data->content ?>
                        </div>
                    </div>
                    <div class="school_main_con" @if($nav == 'major') style="display: block;" @else style="display: none;" @endif>
                        <div class="school-major"></div>
                    </div>
                    <div class="school_main_con" @if($nav == 'line') style="display: block;" @else style="display: none;" @endif>
                        <div class="school-major-line">

                        </div>
                    </div>
                </div>
            </div>
            <div class="s_r">
                <div class="fac_info fac_rlist">
                    <div class="ner_title">
                        <i class="ic_line ic_ner_t"></i>
                        <h3>热门高校</h3>
                    </div>
                    <ul class="fac_hot_list">
                        @if($school_hot)
                            @foreach($school_hot as $v)
                                <li><a href="{{url("/home/school/detail/".$v['id'].".html")}}" target="_blank">{{$v['name']}}</a></li>
                            @endforeach
                        @endif
                    </ul>
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
            $(".school_main_con").css("display","none");
            $(".school_main_con").eq(i).css("display","block");
        };
    </script>
@endsection


