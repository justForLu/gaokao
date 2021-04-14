$(document).ready(function () {
	layer.config({
		skin: 'layer_user'
	});
	var $fix_tool = $('#fix_tool');
	var $fix_tool_top = $("#tool_item_top");

	if ($fix_tool.length) {
		/*回到顶部*/
		var topScrollTimer = null;
		var scrollDot = 250;
		var tops = $(document).scrollTop();
		var isIndex = $('#fix_tool').hasClass("fix_tool_index");
		if (tops > scrollDot) {
			showTotop(true, isIndex);
		} else {
			showTotop(false, isIndex);
		}
		function showTotop(type, isIndex) {
			if (!isIndex) {
				if (type) {
					$fix_tool_top.fadeIn();
				} else {
					$fix_tool_top.fadeOut();
				}
			} else {
				if (type) {
					$fix_tool.fadeIn();
				} else {
					$fix_tool.fadeOut();
				}
			}
		}
		$(window).on("scroll", function () {
			if (topScrollTimer) {
				clearTimeout(topScrollTimer);
			}
			topScrollTimer = setTimeout(function () {
				if ($(document).scrollTop() > scrollDot) {
					showTotop(true, isIndex);
				} else {
					showTotop(false, isIndex);
				}
			}, 200);
		});
		$fix_tool.on('click', '#tool_item_top', function (event) {
			event.preventDefault();
			$("html,body").stop().animate({ scrollTop: 0 }, 500);
		});
	};

	var browser = {
		version: function () {
			var b = navigator.userAgent;
			return {
				trident: /Trident/i.test(b),
				presto: /Presto/i.test(b),
				webKit: /AppleWebKit/i.test(b),
				gecko: /Gecko/i.test(b) && !/KHTML/i.test(b),
				mobile: /AppleWebKit.*Mobile.*/i.test(b),
				ios: /\(i[^;]+;( U;)? CPU.+Mac OS X/i.test(b),
				android: /Android/i.test(b) || /Linux/i.test(b),
				windowsphone: /Windows Phone/i.test(b),
				iPhone: /iPhone/i.test(b),
				iPad: /iPad/i.test(b),
				MicroMessenger: /MicroMessenger/i.test(b),
				webApp: !/Safari/i.test(b),
				edge: /edge/i.test(b),
				weibo: /Weibo/i.test(b),
				uc: /UCBrowser/i.test(b),
				qq: /MQQBrowser/i.test(b),
				baidu: /Baidu/i.test(b)
			};
		}(),
		language: (navigator.browserLanguage || navigator.language).toLowerCase(),
		lteIE: function (b) {
			return $.browser.msie && parseInt($.browser.version) <= b;
		}
	};
});

function shareStatisticsFunc(){
	// 添加分享统计代码 c.k 20191205
	if (typeof(share_statistics_config) !== 'undefined') {
		console.log('share statistics ready')
		var cfg = share_statistics_config;
		$.ajax({
			url: cfg.send_url,
			type: 'get',
			dataType: 'jsonp',
			crossDomain:true,
			data:{type:cfg.type, source:cfg.source, resId:cfg.resId},
			success:function(d){ console.log(d) }
		});
	}
}