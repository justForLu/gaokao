
  function tabInit(a, b, act,callback) {
    a.on(act, function(e) {
      e.stopPropagation();
      var inde = a.index(this);
      a.removeClass("active").eq(inde).addClass("active");
      b.hide().eq(inde).show();
      if (callback) {callback(e,inde)};
    })
  }

  function load_script(xyUrl, callback){
      var head = document.getElementsByTagName('head')[0];
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = xyUrl;
      //借鉴了jQuery的script跨域方法
      script.onload = script.onreadystatechange = function(){
          if((!this.readyState || this.readyState === "loaded" || this.readyState === "complete")){
              callback && callback();
              // Handle memory leak in IE
              script.onload = script.onreadystatechange = null;
              if ( head && script.parentNode ) {
                  head.removeChild( script );
              }
          }
      };
      // Use insertBefore instead of appendChild  to circumvent an IE6 bug.
      head.insertBefore( script, head.firstChild );
  }

  //console.log(alet.alet);
  $(document).ready(function($) {
//    console.log("这里是首页");

    /*弹窗初始化*/
    layer.config({
      skin: 'layer_user'
    })
    layer.ready(function(){});

    var ti;
    /**首页焦点图***/
      $(".num span").hover(
        function(){
          //进入变化效果
          clearTimeout(ti);
          var mo=$(this).parent(".num");
          var i=mo.children("span").removeClass("cur").index($(this).addClass("cur"));
          $(".pics").children("li").hide().eq(i).show();
        },
        function(){
          //移出后设置下一个timeout
          $(this).mouseover();
          var mo=$(this).parent(".num");
          var i=$(this).index();
          var s=mo.children("span").size();
          ti=setTimeout(function(){mo.children("span").eq((i+1)%s).mouseout();},5000);
        }
      );
      $(".num span").eq(0).mouseout();


      //搜索滑动条
      if ($('#i_nstSlider').length > 0) {
         $('#i_nstSlider').nstSlider({
          "rounding":500,
          "left_grip_selector": ".leftGrip",
          "value_changed_callback": function(cause, leftValue, rightValue) {
            $(".leftLabel").html(leftValue+"元以上");
          }
        });
      }


      //城市选择弹出层
      /*$(".site_select").hover(
        function(){
          $(".cur_site").addClass("cur_site_hov");
        },
        function(){
          $(".cur_site").removeClass("cur_site_hov");
        }
      )*/

      // 首页 公告滚动


      /*var ind_nt1 = new Swiper('#ind_nt1',{
        mode: 'vertical',
        autoplay: 3000,
        speed:500
      })
      var ind_nt2 = new Swiper('#ind_nt2',{
        mode: 'vertical',
        autoplay: 3000,
        speed:500
      })*/


      //搜索弹出层
      $(".on_pop_div").click(function(){
        $(".pop_div").hide();
        $(this).next(".pop_div").show();
      });
      //弹出层赋值
      $(".pop_div span").click(function(){
        $(this).parent(".pop_div").hide().prev(".on_pop_div").val($(this).text());
      });

      $("body").bind("click", function (evt) {if ($(evt.target).parents(".pop_input").size() == 0) {$(".pop_div").hide();}});


      //企业详情图片切换
      $(".q_smallpic li").click(function(){
        $(".q_smallpic li").removeClass("cur");
        $(".q_bigpic img").eq(0).attr("src",$(this).addClass("cur").find("img").attr("src"));
      });


      //门店详情切换
      $(".dm_head a").click(function(){
        $(".dm_cont li").hide().eq($(".dm_head a").removeClass("cur").index($(this).addClass("cur"))).show();
      });


      //登录方式切换
      $("#login_meth a").click(function(){
        $(".login_item").hide().eq($("#login_meth a").removeClass("cur").index($(this).addClass("cur"))).show();
      });

   // 首页 公告滚动


      var ind_nt1 = new Swiper('#ind_nt1', {
        mode: 'vertical',
        autoplay: 3000,
        loop: true,
        speed: 500
      });
      var ind_nt2 = new Swiper('#ind_nt2', {
        mode: 'vertical',
        autoplay: 3000,
        loop: true,
        speed: 500
      });

      /*联系我们地图*/
      if ($("#address_map").length > 0) {
        function init_map(){
          var maps = null;

          maps = new BMap.Map("address_map");

          var point = new BMap.Point(113.777168,34.767827);
          maps.centerAndZoom(point, 16);
          maps.setMinZoom(12); //
          maps.enableScrollWheelZoom(true); //滚轮缩放
          maps.addControl(new BMap.NavigationControl()); //缩放尺
          maps.addControl(new BMap.ScaleControl()); //比例尺
          maps.enableInertialDragging(true); //惯性拖拽
          var marker = new BMap.Marker(point);
          maps.addOverlay(marker);
        }
        load_script("http://api.map.baidu.com/getscript?v=2.0&ak=a90enLlR3Wj68Y4UjaGfoTcb&services=&t=20150901171226",function(){
              init_map();
        })


      }

      /*报名弹窗*/
      var join_layer;
      $(document).on('click', '#btn_join_layer', function(event) {
        event.preventDefault();
          join_layer = layer.open({
            type: 1,
            title:"我要报名",
            area: ['800px', 'auto'],
            content: $('#layer_join'),
            success: function(layero, index){
            }
          })
      });
      $("#layer_join").on('click', '.btn_b', function(event) {
        event.preventDefault();
        /*报名数据提交开始*/
        $.ajax({
    		type	: 'POST',	//请求方式
    		url		: "/Public/baoming",		//请求连接
    		dataType: 'json',	//返回json数据
    		data	: $("#signup").serialize(),	//请求数据
    		success	: function(data){
    			if(data.status == 1){
    				/*$("#tip_msg").html('恭喜您，报名成功！<br/>请保持手机畅通，我们会尽快与您联系~');//.text('恭喜您，报名成功！');
*/    				layer.alert('恭喜您，报名成功！<br/>请保持手机畅通，我们会尽快与您联系~', {title:'温馨提示'});
    				layer.close(join_layer);
    			}else{
    				layer.alert(data.msg, {title:'温馨提示'}, function(index){
    					if(data.ret_url){
    						location.href = data.ret_url;
    					}
    					layer.close(index);
    				});
    			}
    		}
    	});
        /*报名数据提交结束*/
      });


      /*推荐*/
      var recom_layer;
      $(document).on('click', '#btn_recom_layer', function(event) {
        event.preventDefault();
        window.open("/Active/active_recom");
          /*recom_layer = layer.open({
            type: 1,
            title:"推荐朋友",
            area: ['800px', 'auto'],
            content: $('#layer_recom'),
            success: function(layero, index){
            }
          })*/
      });
      $("#layer_recom").on('click', '.btn_b', function(event) {
        event.preventDefault();
        /*推荐数据提交开始*/
        $.ajax({
    		type	: 'POST',	//请求方式
    		url		: "Public/recommend",	//请求连接
    		dataType: 'json',	//返回json数据
    		data	: $("#recommend").serialize(),	//请求数据
    		success	: function(data){
    			if(data.status == 1){
    				layer.alert('恭喜您，推荐成功！<br/>请您的朋友保持手机畅通，我们会尽快与他/她联系~', {title:'温馨提示'});
    				layer.close(recom_layer);
    			}else{
    				layer.alert(data.msg, {title:'温馨提示'}, function(index){
    					if(data.ret_url){
    						location.href = data.ret_url;
    					}
    					layer.close(index);
    				});
    			}
    			return false;
    		}
    	});
        /*推荐数据提交结束*/
      });

      /*视频播放*/
      var nvideo = new tvp.VideoInfo();
      nvideo.setVid("j0770uc2k45");
      var player = new tvp.Player();
      player.create({
        width: 328,
        height: 200,
        video: nvideo,
        modId: "news_video",
        autoplay: false,
        oninited: function () {
          /*console.log("video init ready");*/
        },
        onplay: function (a, b) {}

      });


  });






