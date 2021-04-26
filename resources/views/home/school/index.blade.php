@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-fac.css")}}">
@endsection

@section('content')
    <div class="breadcrumb" style="text-align: center;height: 200px;background-color: #ffffff;">
        <img src="{{asset('/assets/home/images/job-banner.png')}}" style="width:1200px;height: 200px;">
    </div>
    <div class="fac_wrap wrap">
        <div class="section fac_filter store_filter">
            <div class="clearfix fac_filter_top">
                <span class="titles">搜索高校</span>
                <div class="fac_filter_search">
                    <form id="search_form" action="{{url("/home/school/index.html")}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="province" class="province" value="@if(isset($params['province']) && !empty($params['province'])) {{$params['province']}} @else 0 @endif">
                        <input type="hidden" name="tag" class="tag" value="@if(isset($params['tag']) && !empty($params['tag'])) {{$params['tag']}} @else 0 @endif">
                        <input type="text" name="name" autocomplete="off" placeholder="请输入高校名称" value="@if(isset($params['name']) && !empty($params['name'])) {{$params['name']}}@endif">
                        <button type="submit" class="btn btn_orange btn_b">搜　索</button>
                    </form>
                </div>
            </div>
            <dl class="lists first province_list">
                <dt class="titles">省份：</dt>
                <dd>
                    <label>
                        <input type="radio" class="province" name="province" @if(!isset($params['province']) || $params['province'] == 0) checked @endif value="0" >
                        <div>不限</div>
                    </label>
                    @if($province)
                        @foreach($province as $v)
                            <label>
                                <input type="radio" class="province" name="province" @if(isset($params['province']) && $params['province'] == $v['id']) checked @endif value="{{$v['id']}}">
                                <div>{{$v['title']}}</div>
                            </label>
                        @endforeach
                    @endif
                </dd>
            </dl>
            <dl class="lists first province_list">
                <dt class="titles">高校标签：</dt>
                <dd>
                    <label>
                        <input type="radio" class="tag" name="tag" @if(!isset($params['tag']) || $params['tag'] == 0) checked @endif value="0" >
                        <div>不限</div>
                    </label>
                    @if($tag)
                        @foreach($tag as $v)
                            <label>
                                <input type="radio" class="tag" name="tag" @if(isset($params['tag']) && $params['tag'] == $v['id']) checked @endif value="{{$v['id']}}">
                                <div>{{$v['name']}}</div>
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
                        <li class="active"><a href="javascript:void(0);">高校列表</a></li>
                    </ul>
                </div>
                <ul class="store_list">
                    @if($list)
                        @foreach($list as $k => $v)
                            <li @if($k == 0) class="first" @endif>
                                <a href="{{url("/home/school/detail/".$v['id'].".html")}}" target="_blank" class="imgs">
                                    <img src="{{$v['logo']}}" alt="{{$v['name']}}" class="imgs">
                                </a>
                                <div class="cons">
                                    <div class="names">
                                        <h4><a href="{{url("/home/school/detail/".$v['id'].".html")}}" target="_blank">{{$v['name']}}</a></h4>
                                    </div>
                                    <div class="school-tag">
                                        @if(!empty($v['tag_arr']))
                                            @foreach($v['tag_arr'] as $val)
                                                <span>{{$val}}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <div class="layui-col-md12 layui-col-sm12">
                    <div id="demo0"></div>
                </div>
            </div>
            <div class="s_r">
                <div class="fac_hot fac_rlist">
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
    <script type="text/javascript">
        //选择省份
        $(".province").on('click',function () {
            var name = $("input[name='name']").val();
            var tag = $("input[name='tag']:checked").val();
            var province = $("input[name='province']:checked").val();
            $(".province").val(province);
            var url = "/home/school/index.html?name="+name+"&province="+province+"&tag="+tag;
            window.location.href = url;
        });
        //选择标签
        $(".tag").on('click',function () {
            var name = $("input[name='name']").val();
            var province = $("input[name='province']:checked").val();
            var tag = $("input[name='tag']:checked").val();
            $(".tag").val(tag);
            var url = "/home/school/index.html?name="+name+"&province="+province+"&tag="+tag;
            window.location.href = url;
        });
    </script>
    <script>
        layui.use(['laypage'], function(){
            var $ = layui.$,
                laypage = layui.laypage;

            //分页
            var name = $("input[name='name']").val();
            var province = $("input[name='province']:checked").val();
            var tag = $("input[name='tag']:checked").val();
            var count = "{{$count}}";
            var url = "{{url('/home/school/index.html')}}";
            var curr = "{{$params['page'] ?? 1}}";
            laypage.render({
                elem: 'demo0',
                count: count, //数据总数
                limit: 10,   //每页条数设置
                curr : curr,
                jump: function(obj, first){
                    page=obj.curr;  //改变当前页码
                    // limit=obj.limit;
                    //首次不执行
                    if(!first){
                        window.location.replace(url+"?name="+name+"&province="+province+"&tag="+tag+"&page="+page)
                    }
                }
            });
        });
    </script>
@endsection


