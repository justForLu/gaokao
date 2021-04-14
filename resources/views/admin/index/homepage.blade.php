@extends('admin.layout.base')

@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    用户总数
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{{$data['user_count']}}</p>
                    <p>平台用户总数</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    今日用户
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{{$data['user_today']}}</p>
                    <p>今日注册用户总数</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    昨日用户
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{{$data['user_yesterday']}}</p>
                    <p>昨日注册用户总数</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    上月用户
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{{$data['user_last_month']}}</p>
                    <p>上月注册用户总数</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    总收益
                </div>
                <div class="layui-card-body layuiadmin-card-list">

                    <p class="layuiadmin-big-font">{{$data['bean_total']}}</p>
                    <p>平台总玉豆收益</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    今日收益
                </div>
                <div class="layui-card-body layuiadmin-card-list">

                    <p class="layuiadmin-big-font">{{$data['bean_today']}}</p>
                    <p>今日平台玉豆收益</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    昨日收益
                </div>
                <div class="layui-card-body layuiadmin-card-list">

                    <p class="layuiadmin-big-font">{{$data['bean_yesterday']}}</p>
                    <p>昨日平台玉豆收益</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    上月收益
                </div>
                <div class="layui-card-body layuiadmin-card-list">

                    <p class="layuiadmin-big-font">{{$data['bean_last_month']}}</p>
                    <p>上月平台玉豆收益</p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm12">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm12">
                    <div class="layui-card">
                        <div class="layui-card-header">用户全国分布</div>
                        <div class="layui-card-body">
                            <div class="layui-row layui-col-space15">
                                <div class="layui-col-sm2">
                                    <table class="layui-table layuiadmin-page-table" lay-skin="line">
                                        <thead>
                                        <tr>
                                            <th>排名</th>
                                            <th>地区</th>
                                            <th>人数</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableData1">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="layui-col-sm2">
                                    <table class="layui-table layuiadmin-page-table" lay-skin="line">
                                        <thead>
                                        <tr>
                                            <th>排名</th>
                                            <th>地区</th>
                                            <th>人数</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableData2">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="layui-col-sm2">
                                    <table class="layui-table layuiadmin-page-table" lay-skin="line">
                                        <thead>
                                        <tr>
                                            <th>排名</th>
                                            <th>地区</th>
                                            <th>人数</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableData3">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="layui-col-sm6">
                                    <div class="layui-carousel layadmin-carousel layadmin-dataview" data-anim="fade" lay-filter="LAY-index-pagethree">
                                        <div carousel-item id="index-user-spread">
                                            <div><i class="layui-icon layui-icon-loading1 layadmin-loading"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        layui.config({
            base: "{{asset('/assets/plugins/layuiadmin/')}}/" //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'carousel','echarts', 'sample', 'senior'], function(){
            var $ = layui.$,
                carousel = layui.carousel,
                echarts = layui.echarts;

            //地图
            $.ajax({
                url:'/admin/index/user_spread',
                method:'get',
                success: function (obj) {
                    var data = obj.data.province;
                    var max = obj.data.max;
                    //表格数据
                    var tableData1 = obj.data.tableData1;
                    var tableData2 = obj.data.tableData2;
                    var tableData3 = obj.data.tableData3;
                    var html1 = "";
                    $.each(tableData1, function (i,j) {
                        html1 += "<tr>";
                        html1 += "<td>" + j.sort + "</td>";
                        html1 += "<td>" + j.name + "</td>";
                        html1 += "<td>" + j.value + "</td>";
                        html1 += "</tr>";
                    });
                    $("#tableData1").html(html1);
                    var html2 = "";
                    $.each(tableData2, function (i,j) {
                        html2 += "<tr>";
                        html2 += "<td>" + j.sort + "</td>";
                        html2 += "<td>" + j.name + "</td>";
                        html2 += "<td>" + j.value + "</td>";
                        html2 += "</tr>";
                    });
                    $("#tableData2").html(html2);
                    var html3 = "";
                    $.each(tableData3, function (i,j) {
                        html3 += "<tr>";
                        html3 += "<td>" + j.sort + "</td>";
                        html3 += "<td>" + j.name + "</td>";
                        html3 += "<td>" + j.value + "</td>";
                        html3 += "</tr>";
                    });
                    $("#tableData3").html(html3);
                    var e = layui.$,
                        a = (layui.carousel,
                            layui.echarts),
                        l = [],
                        t = [{
                            title: {text: "全国用户分布", subtext: ""},
                            tooltip: {trigger: "item"},
                            dataRange: {orient: "horizontal", min: 0, max: max, text: ["高", "低"], splitNumber: 0},
                            series: [{
                                name: "全国用户分布",
                                type: "map",
                                mapType: "china",
                                selectedMode: "multiple",
                                itemStyle: {normal: {label: {show: !0}}, emphasis: {label: {show: !0}}},
                                data: data
                            }]
                        }],
                        i = e("#index-user-spread").children("div"), n = function (e) {
                            l[e] = a.init(i[e], layui.echartsTheme), l[e].setOption(t[e]), window.onresize = l[e].resize
                        };
                    i[0] && n(0)
                }
            });
        })
    </script>
@endsection
