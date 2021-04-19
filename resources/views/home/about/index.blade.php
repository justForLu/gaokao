@extends('home.layout.base')

@section('content')
    <div class="wrap">
        <div>
            <img class="main" src="{{asset("/assets/home/images/about-banner.png")}}" style="width: 100%">
        </div>
    </div>
    <div class="about-section">
        <div class="about-company">
            <div class="about-title">
                <p class="title">公司简介</p>
                <span></span>
            </div>
            <div class="company-box">
                <div class="company-img">
                    <img src="{{asset('/assets/home/images/about-company.png')}}" alt="">
                </div>
                <div class="company-dec">
                    <p>好工人工作网，一款蓝领人力资源O2O平台，以其独特的经营模式，为蓝领打工者提供全程求职服务，为合作门店提供长期招聘资源，实现可持续发展。 坚守本心：坚持“让求职者高薪就业，让从业者终身受益”使命，利用互联网技术对传统劳务重新分工赋能，开创线上+线下一体化服务新模式。线上共享信息资源，线下门店一站式专业服务到底，减少传统中间劳务层级，优化求职服务环节，大大提升了蓝领务工体验与待遇，为用户创造更大价值。 </p>
                    <p>资源丰富：与200多家劳务公司结成战略合作伙伴，海量诚信名企资源，保障客户权益同时，亦增加其可选性。截止目前，牛职工作网服务领域覆盖河南、山东、山西、河北、吉林等地，并不断辐射拓展珠三角、京津冀经济圈劳务资源，已成为蓝领人力资源服务的行业影响力品牌。</p>
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
                        <div class="address">公司总部：郑州市金水区CBD</div>
                        <div class="fangshi">
                            <div class="mobile">手机：13988886666</div>
                            <div class="phone">固话：（0371）88889999</div>
                            <div class="email">邮箱：haogongren@163.com</div>
                        </div>
                    </div>
                    <div class="about-map" id="about_map">

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
            center:[113.727103,34.770438],
            zoom:14
        });
        var marker = new AMap.Marker({
            icon: "{{asset('/assets/home/images/marker.gif')}}",
            position: [113.727103,34.770438]
        });
        map.add(marker);
    </script>

@endsection


