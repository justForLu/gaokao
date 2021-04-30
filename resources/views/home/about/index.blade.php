@extends('home.layout.base')

@section('content')
    <div class="wrap">
        <div style="height: 400px;">
            <img class="main" src="{{asset("/assets/home/images/about-banner.png")}}" style="height: 400px; width: 100%">
        </div>
    </div>
    <div class="about-section">
        <div class="about-company">
            <div class="about-title">
                <p class="title">中夏教育</p>
                <span></span>
            </div>
            <div class="company-box">
                <div class="company-img">
                    <img src="{{asset('/assets/home/images/about-company.png')}}" alt="">
                </div>
                <div class="company-dec">
                    <p>中夏教育是内蒙古通辽市一家专业的教育机构，师资力量十分强大，针对高三学生的一对一提高班。中夏教育是内蒙古通辽市一家专业的教育机构，师资力量十分强大，针对高三学生的一对一提高班。中夏教育是内蒙古通辽市一家专业的教育机构，师资力量十分强大，针对高三学生的一对一提高班。</p>
                    <p>中夏教育是内蒙古通辽市一家专业的教育机构，师资力量十分强大，针对高三学生的一对一提高班。中夏教育是内蒙古通辽市一家专业的教育机构，师资力量十分强大，针对高三学生的一对一提高班。中夏教育是内蒙古通辽市一家专业的教育机构，师资力量十分强大，针对高三学生的一对一提高班。中夏教育是内蒙古通辽市一家专业的教育机构，师资力量十分强大，针对高三学生的一对一提高班。中夏教育是内蒙古通辽市一家专业的教育机构，师资力量十分强大，针对高三学生的一对一提高班。</p>
                </div>
            </div>
        </div>
        <div class="about-contact">
            <div>
                <div class="about-title">
                    <p class="title">联系我们</p>
                    <span></span>
                </div>
                <div class="contact-box">
                    <div class="contact-info">
                        <div class="address">中夏教育：通辽市通辽市通辽市</div>
                        <div class="fangshi">
                            <div class="mobile">手机：13988886666</div>
                            <div class="phone">固话：（0371）88889999</div>
                            <div class="email">邮箱：zhongxia@163.com</div>
                        </div>
                    </div>
                    <div class="about-map" id="about_map">

                    </div>
                </div>
            </div>
        </div>
        <div class="about-feedback">
            <div class="feedback-title">咨询反馈</div>
            <div class="layui-form" lay-filter="feedback-submit">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label class="layui-form-label">姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" placeholder="请输入姓名" value="" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="mobile" placeholder="请输入手机号" value="" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-inline">
                        <input type="text" name="email" placeholder="请输入邮箱" value="" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">内容</label>
                    <div class="layui-input-inline">
                        <textarea name="content" placeholder="请输入内容" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-inline">
                        <button class="feedback-submit" lay-submit lay-filter="feedback-submit">
                            提交
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://webapi.amap.com/maps?v=1.4.15&key=a9ef099582b8abffad85df7a65246f36"></script>
    <script type="text/javascript">
        var map = new AMap.Map('about_map', {
            center:[122.263119,43.617429],
            zoom:14
        });
        var marker = new AMap.Marker({
            icon: "{{asset('/assets/home/images/marker.gif')}}",
            position: [122.263119,43.617429]
        });
        map.add(marker);
    </script>
    <script>
        layui.use(['form'], function(){
            var $ = layui.$,
                form = layui.form;

            form.render(null, 'feedback-submit');
            form.on('submit(feedback-submit)',function (data) {
                var field = data.field; //获取提交的字段
                //提交数据
                $.ajax({
                    url: '/home/feedback',
                    method: 'post',
                    data: field,
                    success: function (res) {
                        if(res.code === 200){
                            var msg = res.msg ? res.msg : '提交成功';
                            layer.msg(msg,{time:3000}, function(){
                                location.href = '/home/about/index.html';
                            });
                        }else{
                            var msg = res.msg ? res.msg : '提交失败';
                            layer.msg(msg);
                        }
                    }
                });
                return false;  //防止表单提交两次
            });
        });
    </script>
@endsection


