@extends('home.layout.base')

@section('styles')
    <link rel="stylesheet" href="{{asset("/assets/home/css/style-news.css")}}">
@endsection

@section('content')
    <div class="breadcrumb">

    </div>
    <div class="wrap wrap_news">
        <div class="section clearfix">
            <div class="ne_fl">
                <div class="ne_pg_main">
                    <div class="ne_pg">
                        <div class="ne_pg_title">
                            <h2>{{$data->title}}</h2>
                            <p><span>浏览次数：{{$data->read}}次</span><span>发布时间：{{$data->create_time}}</span></p>
                        </div>
                        <div class="ne_pg_con">
                            <?php echo $data->content ?>
                        </div>
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
    <script src="{{asset("/assets/home/js/tvp.player_v2_jq.js")}}"></script>
    <script src="{{asset("/assets/home/js/news.js")}}"></script>
@endsection


