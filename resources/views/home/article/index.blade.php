@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-news.css")}}">
@endsection

@section('content')
    <div class="wrap ne_nav" style="text-align: center;height: 400px;background-color: #ffffff;">
        <img src="{{asset('/assets/home/images/news-banner.png')}}" style="width:1200px;height: 400px;">
    </div>

    <div class="wrap wrap_news">
        <div class="section clearfix">
            <div class="ne_fl">

                <div class="ne_xlist_main">
                    <ul class="ne_xlist">
                        @if($list)
                            @foreach($list as $data)
                                <li>
                                    <div class="cons">
                                        <h3><a href="{{url("/home/article/detail/".$data->id.".html")}}">{{$data->title}}</a></h3>
                                        <a href="{{url("/home/article/detail/".$data->id.".html")}}">
                                        <p>{{$data->introduce}}</p>
                                        <span class="times">
                                            <i class="ic ic_ne_time"></i>{{$data->create_time}}
                                            <i class="ic ic_yueduliang" style="margin-left: 15px;"></i>{{$data->read}}
                                        </span>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    @include('home.public.pages')
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
                                    <a href="{{url("/home/article/detail/".$v['id'].".html")}}" title="{{$v['title']}}">
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
@endsection


