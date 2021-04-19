@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-fac.css")}}">
@endsection

@section('content')
    <div class="breadcrumb">
        <ul class="section">
            <li>您现在的位置：</li>
            <li>
                <a href="{{url("/home/index.html")}}">首页</a>
                <span class="divider">&gt;</span>
            </li>
            <li>
                <a href="{{url("/home/shop/index.html")}}">找门店</a>
                <span class="divider">&gt;</span>
            </li>
            <li class="active">{{$data->name}}</li>
        </ul>
    </div><div class="fac_wrap wrap">
        <div class="section">
            <div class="fac_top clearfix">
                <div class="fac_imgshow fac_imgswiper" id="fac_imgshow">
                    <div class="view">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @if($data->picture_path)
                                    @foreach($data->picture_path as $pic)
                                        <div class="swiper-slide">
                                            <a href="javascript:void(0);" >
                                                <img src="{{$pic}}" alt="">
                                                <span class="fix_opt">共{{$count_pic}}张照片</span>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="preview">
                        <a class="arrow-left ic_fac_arrowl" href="javascript:void(0);"></a>
                        <a class="arrow-right ic_fac_arrowr" href="javascript:void(0);"></a>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @if($data->picture_path)
                                    @foreach($data->picture_path as $pic)
                                        <div class="swiper-slide active-nav">
                                            <img src="{{$pic}}" alt="">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fac_top_info sto_top_info">
                    <div class="fac_top_acts">
                        <a href="javascript:collect_func();" class="btn_collect @if($is_collect) hover @endif" id="collect" data-collect="{{$is_collect}}" data-type="2" data-userid="{{$user_id}}" data-id="{{$data->id}}" title="点击收藏">
                            <i class="ic ic_fac_collect"></i>
                            <i class="ic ich ic_fac_collect_hover"></i>
                            <span class="collect-info">@if($is_collect)取消收藏@else收藏@endif</span>
                        </a>
                    </div>
                    <p class="names">{{$data->name}}</p>

                    <p class="des">
                        <span class="titles">招聘岗位：</span>{{$data->job}} <br>
                        <span class="titles">招聘人数：</span>{{$data->num}}人
                        <span class="des_not"><i class="ic ic_fac_joined"></i>已有<em>{{$count_enroll}}人</em>报名</span><br>
                        <span class="titles">服务宗旨：</span>{{$data->purpose}}<br>
                        <span class="titles">门店地址：</span>{{$data->address}}<a href="javascript:largeMap('{{$data->longitude}}', '{{$data->latitude}}', '{{$data->address}}')" class="des_not"><i class="ic ic_fac_address"></i>电子地图</a>
                    </p>

                    <div class="fac_top_phone sto_top_phone">
                        <span class="phones">
                            <i class="ic ic_sto_tphone"></i>
                            @if($data->phone || $data->mobile)
                                {{$data->phone}}&nbsp;&nbsp;&nbsp;{{$data->mobile}}
                            @else
                                获取服务热线中...
                            @endif
                        </span>
                        <span class="fac_acts_btns">
                            <a href="javascript:void(0);" class="btn btn_blue btn_recom btn_join_layer" id="btn_join_layer">
                                <i class="ic ic_fac_join"></i>我要报名
                            </a>
                        </span>
                    </div>
                    <p class="sto_top_note">郑重声明：本站所有发布招聘信息都经过工立方官方核实，100%真实有效，请大家放心报名！</p>
                </div>
            </div>
        </div>

        <div class="fac_main section clearfix">
            <div class="s_l">
                <div class="sto_main">
                    <div class="fac_list_tab">
                        <ul id="sto_contab">
                            <li class="active"><a href="javascript:select_item(0);">招聘企业</a></li>
                            <li><a href="javascript:select_item(1);">本地招聘</a></li>
                            <li><a href="javascript:select_item(2);">就业顾问</a></li>
                            <li><a href="javascript:select_item(3);">门店服务</a></li>
                        </ul>
                        <span class="nums"></span>
                    </div>
                    <div class="sto_main_con" style="display: block;">
                        <ul class="fac_list">
                            @foreach($recruit_list as $k => $rec)
                                <li @if($k == 0) class="first" @endif>
                                    <a href="{{url("/home/recruit/detail/".$rec['id'].".html?shop_id=".$data->id)}}" target="_blank" class="imgs">
                                        <img src="{{$rec['factory']['image']}}" alt="{{$rec['title']}}" class="imgs">
                                    </a>
                                    <div class="cons">
                                        <div class="names">
                                            <span class="prices"><em>{{$rec['salary_up']}}-{{$rec['salary_down']}}</em>元/月</span>
                                            <h4><a href="{{url("/home/recruit/detail/".$rec['id'].".html?shop_id=".$data->id)}}" target="_blank">{{$rec['title']}}</a></h4>
                                        </div>
                                        <p class="des">
                                            <a href="javascript:largeMap(&#39;{{$rec['factory']['longitude']}}&#39;, &#39;{{$rec['factory']['latitude']}}&#39;, &#39;{{$rec['factory']['address']}}&#39;)" class="to_map">
                                                <i class="ic ic_fac_lmap"></i>电子地图
                                            </a>
                                            <span class="titles">招聘人数：</span>{{$rec['num']}}人 <span class="titles title2">企业规模：</span>{{$rec['factory']['scale']}}人<br>
                                            <span class="titles">招聘岗位：</span>@if($rec['posts_arr']) @foreach($rec['posts_arr'] as $pos) <span class="tp">{{$pos['name']}}</span> @endforeach @endif<br>
                                            <span class="titles">招聘要求：</span>@if($rec['edu_ask']) {{$rec['edu_ask']}} @endif<i class="line"></i>
                                            @if($rec['sex_ask']) {{$rec['sex_ask']}} <i class="line"></i>@endif @if($rec['age_ask']) {{$rec['age_ask']}} <i class="line"></i> @endif
                                        </p>
                                        <p class="fac_tag">
                                            <a href="{{url("/home/recruit/detail/".$rec['id'].".html?shop_id=".$data->id)}}" target="_blank" class="to_fac btn btn_blue btn_m btn_round">查看</a>
                                            @if($rec['label_arr'])
                                                @foreach($rec['label_arr'] as $lab)
                                                    <em>{{$lab['name']}}</em>
                                                @endforeach
                                            @endif
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="sto_main_con" style="display: none;">
                        <ul class="store_local">
                            <li class="fac_queitem ic_type type2">暂无本地招聘信息</li>
                        </ul>
                    </div>
                    <div class="sto_main_con">
                        <ul class="store_adv_list clearfix">
                        </ul>
                    </div>
                    <div class="sto_main_con" >
                        <ul class="store_service">
                            <li>
                                <i class="ic ic_sto_t1"></i>
                                <div class="con">
                                    <h4>就业指导</h4>
                                    <p>为您宣传讲解就业问题，根据您的情况精准挑选合适的工厂，帮您找到满意的工作为止。</p>
                                </div>
                            </li>
                            <li>
                                <i class="ic ic_sto_t2"></i>
                                <div class="con">
                                    <h4>随时入职</h4>
                                    <p>咨询、报名进厂打工不收取您任何费用，选择满意工厂后，随时从老家一趟车直送您到厂门口。</p>
                                </div>
                            </li>
                            <li>
                                <i class="ic ic_sto_t3"></i>
                                <div class="con">
                                    <h4>跟踪服务</h4>
                                    <p>工立方在每一家企业都安排有驻厂老师，24小时随时到下车地点接您，并安排临时住宿。驻厂老师全程陪同您面试、入职、办理手续，并随您同吃同住，及时解决您工作、生活中遇到的任何问题。</p>
                                </div>
                            </li>
                            <li>
                                <i class="ic ic_sto_t4"></i>
                                <div class="con">
                                    <h4>无忧保障</h4>
                                    <p>本店会第一时间为您解答关于就业、婚恋等疑问，并为您提供优质相亲平台七月七婚恋网，有工作有对象，解决您的后顾之忧！</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="s_r">

                <div class="fac_info fac_rlist">
                    <div class="ner_title">
                        <i class="ic_line ic_ner_t"></i>
                        <h3>联系门店</h3>
                    </div>
                    <div class="fac_maps">
                        <div id="fac_maps" data-longitude="{{$data->longitude}}" data-latitude="{{$data->latitude}}" style="height: 190px;"></div>
                        <div class="con">
                            <p><span class="titles">门店地址：</span>{{$data->address}}</p>
                            <p><span class="titles">报名电话：</span>{{$data->phone}}<br>{{$data->mobile}}</p>
                        </div>
                    </div>
                </div>

            </div>
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
                    <label class="form-label" for="">报名门店：</label>
                    <div><input type="hidden" name="shop_id" value="{{$data->id}}"></div>
                    <div class="form-field form-field_txt">{{$data->name}}</div>
                </div>
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
        <div class="layer_ask layers" id="layer_ask">
            <p style="text-align: right">
                <span class="close_ask" style="padding: 8px; color: #999;">关闭</span>
            </p>
            @if($user_id)
                <form action="{{url("/home/question/ask")}}" style="padding-top: 50px;" class="form form-horizontal J_ajaxForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="type" value="2" />
                    <input type="hidden" name="shop_id" value="{{$data->id}}" />
                    <input type="hidden" name="user_id" value="{{$user_id}}" />
                    <div class="form-item">
                        <label class="form-label" for="">问题：</label>
                        <div class="form-field">
                            <input type="text" placeholder="请输入您的问题" style="width: 60%;border: 1px solid #dadbdf;padding: 4px;height: 35px;line-height: 35px;border-radius: 4px;" name="title">
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label" for="">是否显示昵称：</label>
                        <div class="form-field">
                            <input type="radio" name="is_show" checked value="1"><span style="padding-left: 3px;">是</span>
                            <input type="radio" name="is_show" value="0"><span style="padding-left: 3px;">否</span>
                        </div>
                    </div>

                    <div class="form-item">
                        <button type="submit" class="btn btn_b btn_orange J_ajax_submit_btn">确定</button>
                    </div>
                </form>
            @else
                <p style="height: 200px;line-height: 200px;text-align: center;">
                    <a href="{{url("/home/login")}}" target="_blank" style="color: #ff6c00;">请先登录</a>
                </p>
            @endif
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
        $("#btn_join_layer").click(function () {
            $("#layer_join").css({"position": "fixed","bottom": "400px","left": "30%","background-color": "#fff","z-index": 99999999,"width": "40%","display":"block"});
        });
        $(".close_join").click(function () {
            $("#layer_join").hide();
            $(".layui-layer-shade").removeAttr("style");
            $(".layui-layer-shade").removeAttr("class");
        });
        $("#btn_ask_layer").click(function () {
            $("#layer_ask").css({"position": "fixed","bottom": "400px","left": "30%","background-color": "#fff","z-index": 99999999,"width": "40%","display":"block"});
        });
        $(".close_ask").click(function () {
            $("#layer_ask").hide();
            $(".layui-layer-shade").removeAttr("style");
            $(".layui-layer-shade").removeAttr("class");
        });
    </script>
@endsection


