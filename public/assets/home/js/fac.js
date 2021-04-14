function load_script(xyUrl, callback) {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = xyUrl;
    //借鉴了jQuery的script跨域方法
    script.onload = script.onreadystatechange = function () {
        if (!this.readyState || this.readyState === "loaded" || this.readyState === "complete") {
            callback && callback();
            // Handle memory leak in IE
            script.onload = script.onreadystatechange = null;
            if (head && script.parentNode) {
                head.removeChild(script);
            }
        }
    };
    // Use insertBefore instead of appendChild  to circumvent an IE6 bug.
    head.insertBefore(script, head.firstChild);
}

function tabInit(a, b, act, callback) {
	  a.on(act, function (e) {
	    e.stopPropagation();
	    var inde = a.index(this);
	    a.removeClass("active").eq(inde).addClass("active");
	    b.hide().eq(inde).show();
	    if (callback) {
	      callback(e, inde);
	    };
	  });
	}

function initMap() {
    var longitude = $("#fac_maps").data("longitude");
    var latitude = $("#fac_maps").data("latitude");
    // 百度地图API功能
    var map = new BMap.Map("fac_maps");
    map.enableScrollWheelZoom(true); //滚动鼠标可缩放
    if (longitude && latitude) {
        var point = new BMap.Point(longitude, latitude);
        map.centerAndZoom(point, 13);
        map.addControl(new BMap.NavigationControl());
        map.addControl(new BMap.ScaleControl());
        map.addOverlay(new BMap.Marker(point));
    } else {
        // 创建地址解析器实例
        var myGeo = new BMap.Geocoder();
        // 将地址解析结果显示在地图上,并调整地图视野
        myGeo.getPoint(address, function (point) {
            if (point) {
                map.centerAndZoom(point, 13);
                map.addOverlay(new BMap.Marker(point));
            }
        }, city);
    }
}

//查看大地图
function largeMap(longitude, latitude, address) {
    var url = "/home/ejectMap/longitude/latitude/address.html";
    url = url.replace('longitude', longitude).replace('latitude', latitude).replace('address', address);
    //console.log(url);return false;
    var left = $(window).width();
    left = (left - 960) / 2;
    window.open(url, "", "directories=no,height=600,location=no,menuber=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,top=150,width=960,left=" + left);
}

$(document).ready(function () {
    // 本地招聘
    if ($("#lc_recom_list").length > 0) {
        $("#lc_recom_list").on('mouseenter', 'li', function (event) {
          event.preventDefault();
          $("#lc_recom_list li").removeClass('hover');
          $(this).addClass('hover');
        });
    }
    if ($(".fac_imgshow").length > 0) {
    	var previewSwiper;
	    var viewSwiper;

	    var viewSwiper = new Swiper('.view .swiper-container', {
	      onSlideChangeStart: function () {
	        updateNavPosition();
	      }
	    });

	    $('.view .arrow-left,.preview .arrow-left').on('click', function (e) {
	      e.preventDefault();
	      if (viewSwiper.activeIndex == 0) {
	        viewSwiper.swipeTo(viewSwiper.slides.length - 1, 1000);
	        return;
	      }
	      viewSwiper.swipePrev();
	    });
	    $('.view .arrow-right,.preview .arrow-right').on('click', function (e) {
	      e.preventDefault();
	      if (viewSwiper.activeIndex == viewSwiper.slides.length - 1) {
	        viewSwiper.swipeTo(0, 1000);
	        return;
	      }
	      viewSwiper.swipeNext();
	    });

	    var previewSwiper = new Swiper('.preview .swiper-container', {
	      visibilityFullFit: true,
	      slidesPerView: 'auto',
	      onlyExternal: true,
	      onSlideClick: function () {
	        viewSwiper.swipeTo(previewSwiper.clickedSlideIndex);
	      }
	    });

	    function updateNavPosition() {
	      $('.preview .active-nav').removeClass('active-nav');
	      var activeNav = $('.preview .swiper-slide').eq(viewSwiper.activeIndex).addClass('active-nav');
	      if (!activeNav.hasClass('swiper-slide-visible')) {
	        if (activeNav.index() > previewSwiper.activeIndex) {
	          var thumbsPerNav = Math.floor(previewSwiper.width / activeNav.width()) - 1;
	          previewSwiper.swipeTo(activeNav.index() - thumbsPerNav);
	        } else {
	          previewSwiper.swipeTo(activeNav.index());
	        }
	      }
	    }
    }

    if ($("#salary_tab").length >0) {
        tabInit($("#salary_tab li"), $(".salary_con"), "click");
    }

    /*报名弹窗*/
    var join_layer;
    $(document).on('click', '.btn_join_layer', function (event) {
        event.preventDefault();
        join_layer = layer.open({
            type: 1,
            title: "我要报名",
            area: ['800px', 'auto'],
            content: $('#wybm'),
            success: function (layero, index) {}
        });
    });
    $("#layer_join").on('click', '.btn_b', function (event) {
        event.preventDefault();
        layer.close(join_layer);
    });
    var join_layer2;
    $(document).on('click', '#btn_join_layer2', function (event) {
        event.preventDefault();
        join_layer2 = layer.open({
            type: 1,
            title: "我要报名",
            area: ['800px', 'auto'],
            content: $('#wybm'),
            success: function (layero, index) {}
        });
    });
    /*推荐*/
    var recom_layer;
    $(document).on('click', '#btn_recom_layer', function (event) {
        event.preventDefault();
        recom_layer = layer.open({
            type: 1,
            title: "推荐朋友",
            area: ['800px', 'auto'],
            content: $('#tjgy'),
            success: function (layero, index) {}
        });
    });
    $("#layer_recom").on('click', '.btn_b', function (event) {
        event.preventDefault();
        layer.close(recom_layer);
    });

    $(".award_con").hover(function () {
        var $list = $(this).find('.award_list');
        $(this).addClass('active');
    }, function () {
        var $list = $(this).find('.award_list');
        $(this).removeClass('active');
    });

    if ($("#fac_maps").length > 0) {
        load_script("http://api.map.baidu.com/getscript?v=2.0&ak=a90enLlR3Wj68Y4UjaGfoTcb&services=&t=20150901171226", function () {
            initMap();
        });
    }

    if ($(".fac_rank").length > 0) {
        $(".fac_rank").on('mouseenter', 'a', function (event) {
            event.preventDefault();
            $(".fac_rank a").removeClass('hover');
            $(this).addClass('hover');
        });
    }
    /*提问*/
    var ask_layer;
    $(document).on('click', '.btn_ask_layer', function (event) {
        event.preventDefault();
        ask_layer = layer.open({
            type: 1,
            title: "我要提问",
            area: ['800px', 'auto'],
            content: $('#wybm'),
            success: function (layero, index) {}
        });
    });
    $("#layer_ask").on('click', '.btn_b', function (event) {
        event.preventDefault();
        layer.close(join_ask);
    });
});

//收藏
function collect_func () {
    var type = $("#collect").data("type");
    var user_id = $("#collect").data("userid");
    var id = $("#collect").data("id");
    var is_collect = $("#collect").data("collect");
    var _token = "{{ csrf_token() }}";
    var add_cal = '';
    if(is_collect){
        add_cal = "cal";
    }else{
        add_cal = "add";
    }

    $.post("/home/collect/collect",{type:type,user_id:user_id,id:id,add_cal:add_cal,_token:_token},
        function (data) {
            if(data.status == 200){
                layer.msg(data.msg);
                if(add_cal == 'add'){
                    $("#collect").addClass("btn_collect hover");
                    $(".collect-info").html("取消收藏");
                }else if(add_cal == 'cal'){
                    $("#collect").addClass("btn_collect");
                    $(".collect-info").html("收藏");
                }
            }else {
                layer.msg(data.msg,{
                    time:1000,
                    end:function () {
                        location.href = data.referrer
                    }
                })
            }
        }
    );
}