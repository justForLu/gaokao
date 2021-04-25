@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-news.css")}}">
@endsection

@section('content')
    <div class="wrap ne_nav" style="text-align: center;height: 200px;background-color: #ffffff;">
        <img src="{{asset('/assets/home/images/news-banner.png')}}" style="width:1200px;height: 200px;">
    </div>

    <div class="wrap wrap_news">
        <div class="section clearfix">
            <div class="ne_fl">
                <ul class="article_nav" style='text-align: left'>
                    <li @if(!isset($params['category_id']) || empty($params['category_id'])) class="active" @endif><a href="{{url("/home/article/index.html")}}">全部</a></li>
                    @if(!empty($category))
                        @foreach($category as $v)
                            <li @if(isset($params['category_id']) && $params['category_id'] == $v['id']) class="active" @endif><a href="{{url("/home/article/index.html?category_id=".$v['id'])}}">专业解读</a></li>
                        @endforeach
                    @endif
                </ul>
                <div class="ne_xlist_main">
                    <ul class="ne_xlist">
                        @if($list)
                            @foreach($list as $data)
                                <li>
                                    <div class="cons">
                                        <h3><a href="{{url("/home/article/detail/".$data['id'].".html")}}" target="_blank">{{$data['title']}}</a></h3>
                                        <a href="{{url("/home/article/detail/".$data['id'].".html")}}" target="_blank">
                                        <p>{{$data['introduce']}}</p>
                                        <span class="times">
                                            <i class="ic ic_ne_time"></i>{{$data['create_time']}}
                                            <i class="ic ic_yueduliang" style="margin-left: 15px;"></i>{{$data['read']}}
                                        </span>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="layui-col-md12 layui-col-sm12">
                        <div id="demo0"></div>
                    </div>
                </div>
            </div>
            <div class="ne_fr">
                <div class="ne_rhot ne_rmain">
                    <div class="ner_title">
                        <i class="ic_line ic_ner_t"></i>
                        <h3>热门文章</h3>
                    </div>
                    <ul class="ne_rhot_list clearfix">
                        @if($article)
                            @foreach($article as $v)
                                <li>
                                    <a href="{{url("/home/article/detail/".$v['id'].".html")}}" target="_blank">
                                        <p>{{$v['title']}}</p>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset("/assets/home/js/news.js")}}"></script>
    <script>
        layui.use(['laypage'], function(){
            var $ = layui.$,
                laypage = layui.laypage;
            var category_id = "{{$params['category_id'] ?? 0}}";
            var count = "{{$count}}";
            var url = "{{url('/home/article/index.html?category_id=')}}";
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
                        window.location.replace(url+category_id+"&page="+page)
                    }
                }
            });
        });
    </script>
@endsection


