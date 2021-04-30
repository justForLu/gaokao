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
                        <div class="school-province-line">
                            <div class="layui-form">
                                <div class="layui-form-item">
                                    <div class="layui-input-inline">
                                        @if(!empty($province))
                                            <select id="province_province" lay-filter="province_province">
                                                @foreach($province as $v)
                                                    <option value="{{$v['id']}}" @if($province_province == $v['id']) selected @endif>{{$v['title']}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="layui-input-inline">
                                        @if($year_list)
                                            <select id="province_year"  lay-filter="province_year">
                                                @foreach($year_list as $year_v)
                                                    <option value="{{$year_v}}" @if($province_year == $year_v) selected @endif>{{$year_v}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="layui-input-inline">
                                        <select id="province_science" lay-filter="province_science">
                                            <option value="1">理科</option>
                                            <option value="2">文科</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="title-box">
                                <ul class="province-line-title">
                                    <li>年份</li>
                                    <li>批次</li>
                                    <li>文理科</li>
                                    <li>最低线/最低位次</li>
                                    <li>省控线</li>
                                </ul>
                            </div>
                            <div class="province-line-box">
                                @if(!empty($province_line_list))
                                    @foreach($province_line_list as $p_v)
                                        <ul>
                                            <li>{{$p_v['year']}}</li>
                                            <li>{{$p_v['batch_name']}}</li>
                                            <li>{{$p_v['science_name']}}</li>
                                            <li>{{$p_v['min_score']}}/{{$p_v['min_rank']}}</li>
                                            <li>{{$p_v['control_line']}}</li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                            <div class="layui-col-md12 layui-col-sm12">
                                <div id="province_page"></div>
                            </div>
                        </div>
                    </div>
                    <div class="school_main_con" @if($nav == 'line') style="display: block;" @else style="display: none;" @endif>
                        <div class="school-major-line">
                            <div class="layui-form">
                                <div class="layui-form-item">
                                    <div class="layui-input-inline">
                                        @if(!empty($province))
                                            <select id="province" lay-filter="line_province">
                                                <option value="0">请选择省份</option>
                                                @foreach($province as $v)
                                                    <option value="{{$v['id']}}" @if($line_province == $v['id']) selected @endif>{{$v['title']}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="layui-input-inline">
                                        @if($year_list)
                                            <select id="line_year"  lay-filter="line_year">
                                                @foreach($year_list as $year_v)
                                                    <option value="{{$year_v}}" @if($line_year == $year_v) selected @endif>{{$year_v}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="layui-input-inline">
                                        <select id="science" lay-filter="line_science">
                                            <option value="1">理科</option>
                                            <option value="2">文科</option>
                                        </select>
                                    </div>
                                    <div class="layui-input-inline">
                                        @if(!empty($batch))
                                            <select id="batch" lay-filter="line_batch">
                                                @foreach($batch as $v)
                                                    <option value="{{$v['id']}}">{{$v['name']}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="title-box">
                                <ul class="major-line-title">
                                    <li>专业名称</li>
                                    <li>批次</li>
                                    <li>平均分</li>
                                    <li>最低线/最低位次</li>
                                </ul>
                            </div>
                            <div class="major-line-box">
                                @if(!empty($major_line_list))
                                    @foreach($major_line_list as $m_v)
                                        <ul>
                                            <li>{{$m_v['major_name']}}</li>
                                            <li>{{$m_v['batch_name']}}</li>
                                            <li>{{$m_v['avg_score']}}</li>
                                            <li>{{$m_v['min_score']}}/{{$m_v['min_rank']}}</li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                            <div class="layui-col-md12 layui-col-sm12">
                                <div id="line_page"></div>
                            </div>
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
        layui.use(['form','laypage'], function(){
            var $ = layui.$,
                form = layui.form,
                laypage = layui.laypage;

            //省录取线分页
            var province_province = "{{$params['province_province'] ?? 18}}";
            var province_year = "{{$params['province_year'] ?? 0}}";
            var province_science = "{{$params['province_science'] ?? 0}}";
            var province_count = "{{$province_count ?? 0}}";
            var province_url = "{{url('/home/school/detail/'.$data->id.'.html?nav=province')}}";
            var province_curr = "{{$params['province_curr'] ?? 1}}";
            form.on("select(province_province)", function (data) {
                province_province = data.value;
                province_year = $("#province_year  option:selected").val();
                province_science = $("#province_science  option:selected").val();
                window.location.replace(province_url+"&province_province="+province_province+"&province_year="+province_year+
                    "&province_science="+province_science+"&province_curr="+province_curr);
            });
            form.on("select(province_year)", function (data) {
                province_province = $("#province_province  option:selected").val();
                province_year = data.value;
                province_science = $("#province_science  option:selected").val();
                window.location.replace(province_url+"&province_province="+province_province+"&province_year="+province_year+
                    "&province_science="+province_science+"&province_curr="+province_curr);
            });
            form.on("select(province_science)", function (data) {
                province_province = $("#province_province  option:selected").val();
                province_year = $("#province_year  option:selected").val();
                province_science = data.value;
                window.location.replace(province_url+"&province_province="+province_province+"&province_year="+province_year+
                    "&province_science="+province_science+"&province_curr="+province_curr);
            });
            laypage.render({
                elem: 'province_page',
                count: province_count, //数据总数
                limit: 10,   //每页条数设置
                curr : province_curr,
                jump: function(obj, first){
                    province_curr=obj.curr;  //改变当前页码
                    // limit=obj.limit;
                    //首次不执行
                    if(!first){
                        window.location.replace(province_url+"&province_province="+province_province+"&province_year="+province_year+
                            "&province_science="+province_science+"&province_curr="+province_curr)
                    }
                }
            });

            //专业录取线分页
            var line_province = "{{$params['line_province'] ?? 18}}";
            var line_year = "{{$params['line_year'] ?? 0}}";
            var line_science = "{{$params['line_science'] ?? 0}}";
            var line_batch = "{{$params['line_batch'] ?? 0}}";
            var line_count = "{{$line_count ?? 0}}";
            var line_url = "{{url('/home/school/detail/'.$data->id.'.html?nav=line')}}";
            var line_curr = "{{$params['page'] ?? 1}}";
            form.on("select(line_province)", function (data) {
                line_province = data.value;
                line_year = $("#line_year  option:selected").val();
                line_science = $("#line_science  option:selected").val();
                line_batch = $("#line_batch  option:selected").val();
                window.location.replace(url+"&line_province="+line_province+"&line_year="+line_year+"&line_science="+
                    line_science+"&line_batch="+line_batch+"&line_curr="+line_curr);
            });
            form.on("select(line_year)", function (data) {
                line_province = $("#line_province  option:selected").val();
                line_year = data.value;
                line_science = $("#line_science  option:selected").val();
                line_batch = $("#line_batch  option:selected").val();
                window.location.replace(url+"&line_province="+line_province+"&line_year="+line_year+"&line_science="+
                    line_science+"&line_batch="+line_batch+"&line_curr="+line_curr);
            });
            form.on("select(line_science)", function (data) {
                line_province = $("#line_province  option:selected").val();
                line_year = $("#line_year  option:selected").val();
                line_science = data.value;
                line_batch = $("#line_batch  option:selected").val();
                window.location.replace(url+"&line_province="+line_province+"&line_year="+line_year+"&line_science="+
                    line_science+"&line_batch="+line_batch+"&line_curr="+line_curr);
            });
            form.on("select(line_batch)", function (data) {
                line_province = $("#line_province  option:selected").val();
                line_year = $("#line_year  option:selected").val();
                line_science = $("#line_science  option:selected").val();
                line_batch = data.value;
                window.location.replace(url+"&line_province="+line_province+"&line_year="+line_year+"&line_science="+
                    line_science+"&line_batch="+line_batch+"&line_curr="+line_curr);
            });

            laypage.render({
                elem: 'line_page',
                count: line_count, //数据总数
                limit: 10,   //每页条数设置
                curr : line_curr,
                jump: function(obj, first){
                    line_curr=obj.curr;  //改变当前页码
                    // limit=obj.limit;
                    //首次不执行
                    if(!first){
                        window.location.replace(line_url+"&line_province="+line_province+"&line_year="+line_year+"&line_science="+
                            line_science+"&line_batch="+line_batch+"&line_curr="+line_curr);
                    }
                }
            });
        });
    </script>
@endsection


