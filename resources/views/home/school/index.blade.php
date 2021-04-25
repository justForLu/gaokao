@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-fac.css")}}">
@endsection

@section('content')
    <div class="breadcrumb" style="text-align: center;height: 400px;background-color: #ffffff;">
        <img src="{{asset('/assets/home/images/job-banner.png')}}" style="width:1200px;height: 400px;">
    </div>
    <div class="fac_wrap wrap">
        <div class="section fac_filter store_filter">
            <div class="clearfix fac_filter_top">
                <span class="titles">搜索服务门店</span>
                <div class="fac_filter_search">
                    <form id="search_form" action="{{url("/home/shop/index.html")}}" method="get">
                        <input type="hidden" name="province_id" class="province_id" value="@if(isset($params['province_id']) && !empty($params['province_id'])) {{$params['province_id']}} @else 0 @endif">
                        <input type="hidden" name="city_id" class="city_id" value="@if(isset($params['city_id']) && !empty($params['city_id'])) {{$params['city_id']}} @else 0 @endif">
                        <input type="text" placeholder="请输入搜索关键字" name="keyword" value="@if(isset($params['keyword']) && !empty($params['keyword'])) {{$params['keyword']}}@endif">
                        <button type="submit" class="btn btn_orange btn_b">搜　索</button>
                    </form>
                </div>
            </div>
            <dl class="lists first province_list">
                <dt class="titles">省份选择：</dt>
                <dd>
                    <label>
                        <input type="radio" checked class="province" name="province" @if(!isset($params['province_id']) || $params['province_id'] == 0) checked @endif value="0" >
                        <div>不限</div>
                    </label>
                    @if($province)
                        @foreach($province as $v)
                            <label>
                                <input type="radio" class="province" name="province" @if(isset($params['province_id']) && $params['province_id'] == $v['id']) checked @endif value="{{$v['id']}}">
                                <div>{{$v['title']}}</div>
                            </label>
                        @endforeach
                    @endif
                </dd>
            </dl>
            <!-- 根据站点获得一个默认城市列表 -->
            <dl class="lists city_list">
                <dt class="titles">城市选择：</dt>
                <dd>
                    <label>
                        <input type="radio" checked class="city" name="city" @if(!isset($params['city_id']) || $params['city_id'] == 0) checked @endif value="0">
                        <div>不限</div>
                    </label>
                    @if($city)
                        @foreach($city as $v)
                            <label>
                                <input type="radio" class="city" name="city" @if(isset($params['city_id']) && $params['city_id'] == $v['id']) checked @endif value="{{$v['id']}}">
                                <div>{{$v['title']}}</div>
                            </label>
                        @endforeach
                    @endif
                </dd>
            </dl>
        </div>
        <div class="fac_main section clearfix">
            <div class="s_l fac_list_main">
                <div class="fac_list_tab">
                    <ul id="fac_list_tab">
                        <li class="active"><a href="javascript:void(0);">门店列表</a></li>
                    </ul>
                    <span class="nums">共为您找到<em>{{$list->total()}}</em>条信息</span>
                </div>
                <ul class="store_list">
                    @if($list)
                        @foreach($list as $k => $v)
                            <li @if($k == 0) class="first" @endif>
                                <a href="{{url("/home/shop/detail/".$v['id'].".html")}}" target="_blank" class="imgs">
                                    <img src="{{$v['image']}}" alt="{{$v['name']}}" class="imgs">
                                </a>
                                <div class="cons">
                                    <div class="names">
                                        <a href="{{url("/home/shop/detail/".$v['id'].".html")}}" target="_blank" class="to_store">门店详情 ></a>
                                        <h4><a href="{{url("/home/shop/detail/".$v['id'].".html")}}" target="_blank">{{$v['name']}}</a></h4>
                                    </div>
                                    <p class="des">
                                        <span class="titles">招聘岗位：</span><em class="nums">{{$v['job_num']}}</em>个<br><span
                                                class="titles">招聘企业：</span><span class="tp">{{$v['factory_name']}}</span><br>
                                        <span class="titles">门店地址：</span>{{$v['address']}} </p>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
                @include('home.public.pages')
            </div>
            <div class="s_r">
                <div class="fac_hot fac_rlist">
                    <div class="ner_title">
                        <i class="ic_line ic_ner_t"></i>
                        <h3>工友推荐</h3>
                    </div>
                    <ul class="fac_hot_list">
                        @if($recruit_hot)
                            @foreach($recruit as $v)
                                <li><a href="{{url("/home/recruit/detail/".$v['id'].".html")}}" target="_blank">{{$v['factory']['name']}} （{{$v['salary_up']}}--{{$v['salary_down']}}元/月）</a></li>
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
    <script type="text/javascript">
        //选择地区
        $(".province").on('click',function () {
            var province = $("input[name='province']:checked").val();
            $(".province_id").val(province);
            var url = "/home/shop/index.html?province_id="+province;
            window.location.href = url;
        });
        $(".city").on('click',function () {
            var province = $("input[name='province']:checked").val();
            var city = $("input[name='city']:checked").val();
            $(".province_id").val(province);
            $(".city_id").val(city);
            var url = "/home/shop/index.html?province_id="+province+"&city_id="+city;
            window.location.href = url;
        });
    </script>
@endsection


