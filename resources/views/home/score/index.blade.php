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
        </div>
        <div class="fac_main section clearfix">
            <div class="score-box">
                <div class="layui-form">
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            @if(!empty($province))
                                <select id="province" lay-filter="score_province">
                                    <option value="0">请选择省份</option>
                                    @foreach($province as $v)
                                        <option value="{{$v['id']}}">{{$v['title']}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="layui-input-inline">
                            <select id="science" lay-filter="score_science">
                                <option value="1">理科</option>
                                <option value="2">文科</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="title-box">
                    <ul class="score-title">
                        <li>省份</li>
                        <li>年份</li>
                        <li>批次</li>
                        <li>文理科</li>
                        <li>分数</li>
                    </ul>
                </div>
                <div class="body-box">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        layui.config({
            base: "{{asset('/assets/plugins/layui/')}}/" //静态资源所在路径
        }).use(['form'], function(){
            var $ = layui.$,
                form = layui.form;

            form.on("select(score_province)", function (data) {
                var province = data.value;
                var science = $("#science  option:selected").val();
                dealScore(province,science);
            });
            form.on("select(score_science)", function (data) {
                var science = data.value;
                var province = $("#province  option:selected").val();
                dealScore(province,science);
            });

            function dealScore(province,science) {
                $.ajax({
                    url: '/home/score/get_list?province='+province+'&science='+science,
                    method: 'get',
                    success: function (res) {
                        if(res.code === 0){
                            var html = "";
                            if(res.data){
                                $.each(res.data, function (k,v) {
                                    if(v.yiben){
                                        html += "<ul class='score-body'>";
                                        html += "<li>" + v.province_name + "</li>";
                                        html += "<li>" + v.year + "</li>";
                                        html += "<li>本科一批</li>";
                                        html += "<li>" + v.science_name + "</li>";
                                        html += "<li>" + v.yiben + "</li>";
                                        html += "</ul>";
                                    }
                                    if(v.erben){
                                        html += "<ul class='score-body'>";
                                        html += "<li>" + v.province_name + "</li>";
                                        html += "<li>" + v.year + "</li>";
                                        html += "<li>本科二批</li>";
                                        html += "<li>" + v.science_name + "</li>";
                                        html += "<li>" + v.erben + "</li>";
                                        html += "</ul>";
                                    }
                                    if(v.sanben){
                                        html += "<ul class='score-body'>";
                                        html += "<li>" + v.province_name + "</li>";
                                        html += "<li>" + v.year + "</li>";
                                        html += "<li>本科三批</li>";
                                        html += "<li>" + v.science_name + "</li>";
                                        html += "<li>" + v.sanben + "</li>";
                                        html += "</ul>";
                                    }
                                    if(v.dazhuan){
                                        html += "<ul class='score-body'>";
                                        html += "<li>" + v.province_name + "</li>";
                                        html += "<li>" + v.year + "</li>";
                                        html += "<li>大专</li>";
                                        html += "<li>" + v.science_name + "</li>";
                                        html += "<li>" + v.dazhuan + "</li>";
                                        html += "</ul>";
                                    }
                                });
                            };
                            $(".body-box").html(html);
                        }
                    }
                })
            }
        })
    </script>
@endsection


