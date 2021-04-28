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
                            <li @if($nav == 'province') class="active" @endif><a href="{{url("/home/school/detail/".$data->id.".html?nav=province")}}">各省分数线</a></li>
                            <li @if($nav == 'line') class="active" @endif><a href="{{url("/home/school/detail/".$data->id.".html?nav=line")}}">专业分数线</a></li>
                        </ul>
                        <span class="nums"></span>
                    </div>
                    <div class="school_main_con" @if($nav == 'desc') style="display: block;" @else style="display: none;" @endif >
                        <div class="school-content">
                            <?php echo $data->content ?>
                        </div>
                    </div>
                    <div class="school_main_con" @if($nav == 'major') style="display: block;" @else style="display: none;" @endif>
                        <div class="school-major">
                            @if(isset($subject) &&!empty($subject))
                                <div class="school-subject">
                                    <ul>
                                        <li>学科评估</li>
                                        <li>
                                            @foreach($subject as $v)
                                                <span>{{$v['name']}}（{{$v['num']}}）</span>
                                            @endforeach
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            @if(isset($country_major) &&!empty($country_major))
                                <div class="country_major">
                                    <ul>
                                        <li>国家特色专业</li>
                                        <li>
                                            @foreach($country_major as $v)
                                                <span>{{$v}}</span>
                                            @endforeach
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            @if(isset($main_major) &&!empty($main_major))
                                <div class="main_major">
                                    <ul>
                                        <li>重点学科专业</li>
                                        <li>
                                            @foreach($main_major as $v)
                                                <span>{{$v}}）</span>
                                            @endforeach
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            @if(isset($king_major) &&!empty($king_major))
                                <div class="king_major">
                                    <ul>
                                        <li>本校王牌专业</li>
                                        <li>
                                            @foreach($king_major as $v)
                                                <span>{{$v}}）</span>
                                            @endforeach
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            @if(isset($major) &&!empty($major))
                                <div class="major-list">
                                    <ul class="major-title">
                                        <li>类别</li>
                                        <li>专业名称</li>
                                    </ul>
                                    @foreach($major as $v)
                                        <ul>
                                            <li>{{$v['category_name']}}</li>
                                            <li>
                                                @if(!empty($v['arr']))
                                                    @foreach($v['arr'] as $val)
                                                        <span>{{$val['name']}}</span>
                                                    @endforeach
                                                @endif
                                            </li>
                                        </ul>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="school_main_con" @if($nav == 'province') style="display: block;" @else style="display: none;" @endif>
                        <div class="school-major-line">

                        </div>
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
@endsection


