$(document).ready(function () {
	$("img.lazy").lazyload({
		effect: "fadeIn",
		threshold: 200
	});

	$(".title").addClass("animate");
	$(".instru").addClass("zoomIn");

	// stp2
	var st2_swp = new Swiper('#st2_swp', {
		loop: true,
		autoplay: 5000,
		paginationClickable: true,
		pagination: '.st2_swp .pagination'
		//slidesPerGroup : 4,

	});
	$("#news_recom_list").hover(function () {
		st2_swp.stopAutoplay();
	}, function () {
		st2_swp.startAutoplay();
	});
	$('.st2_swp .arrow-left').on('click', function (e) {
		e.preventDefault();
		st2_swp.swipePrev();
	});
	$('.st2_swp .arrow-right').on('click', function (e) {
		e.preventDefault();
		st2_swp.swipeNext();
	});

	var stp_showed = {};

	$(window).scroll(function () {
		var _ismobile = false;
		var windowTop = $(window).scrollTop();
		var windowBottom = windowTop + $(window).height();
		var showNum = !_ismobile ? 4 : 16;
		$('.ani-view').each(function () {

			var pageQ1 = $(this).offset().top + $(this).height() / showNum;
			var pageQ3 = $(this).offset().top + $(this).height() / 1;

			if (pageQ1 <= windowBottom && pageQ3 >= windowTop) {

				// stp1
				if (!stp_showed["eq1"] && $(this).hasClass("st1_con")) {
					console.log("list1");
					$(".st1 .fade-in-left2").addClass('fadeInLeft2');
					$(".st1 .fade-in-right2").addClass('fadeInRight2');
					$(".st1 .middle").addClass("showed");
					stp_showed.eq1 = true;
				}

				// stp3
				if (!stp_showed["eq3"] && $(this).hasClass("st3_faclist")) {
					console.log("list3");
					$('#st3_faclist li').each(function (index, el) {
						var $this = $(this);
						setTimeout(function () {
							$this.addClass("showed");
						}, index * 50);
					});

					setTimeout(function () {
						$("#st3_rylist a").each(function (index, el) {
							var $this = $(this);
							setTimeout(function () {
								$this.addClass("showed");
							}, index * 50);
						});
					}, 1350);

					stp_showed.eq3 = true;
				}

				// stp4
				if (!stp_showed["eq4"] && $(this).hasClass("st4_ul")) {
					console.log("list4");
					function $$(id) {
						return document.getElementById(id);
					};
					function countUp(elem, endVal, startVal, duration, decimal) {
						//传入参数依次为 数字要变化的元素，最终显示数字，数字变化开始值，变化持续时间，小数点位数
						var startTime = 0;
						var dec = Math.pow(10, decimal);
						var progress, value;
						function startCount(timestamp) {
							if (!startTime) startTime = timestamp;
							progress = timestamp - startTime;
							value = startVal + (endVal - startVal) * (progress / duration);
							value = value > endVal ? endVal : value;
							value = Math.floor(value * dec) / dec;
							elem.innerHTML = value.toFixed(decimal);
							progress < duration && requestAnimationFrame(startCount);
						}
						requestAnimationFrame(startCount);
					}
					setTimeout(function () {
						countUp($$('counter-value1'), 975600, 0, 1500, 0);
						countUp($$('counter-value2'), 1214500, 0, 1500, 0);
						countUp($$('counter-value3'), 32000, 0, 1500, 0);
						countUp($$('counter-value4'), 127, 0, 1500, 0);
						countUp($$('counter-value5'), 350000, 0, 1500, 0);
						$(".st4_ul li").each(function (index, el) {
							var $this = $(this);
							var height = $this.data('height');
							$this.find('.it_con').animate({ height: height }, 1500, function () {
								$this.find('img').animate({ opacity: 1 }, 200);
							}).css('overflow', 'visible');
						});
					}, 100);

					stp_showed.eq4 = true;
				}

				if (!stp_showed["eq6"] && $(this).hasClass("st6_con")) {
					console.log("list6");
					// stp6
					$('#st6 .num').addClass('showed');
					stp_showed.eq6 = true;
				}
				//				if (!stp_showed["eq7"] && $(this).hasClass("st7")) {
				//					console.log("list7");
				//					// stp7
				//					$('.st7 .img').addClass('showed');
				//					stp_showed.eq7 = true;
				//				}
				if (!stp_showed["eq8"] && $(this).hasClass("st8")) {
					console.log("list8");
					// stp8
					$("#st8_list li").each(function (index, el) {
						var $this = $(this);
						setTimeout(function () {
							$this.find(".con").addClass("showed");
							$this.find('.ic_right').addClass('showed');
						}, index * 800);
					});
					stp_showed.eq8 = true;
				}

				if ($(this).hasClass("pulse")) $(this).addClass('Pulse');
				if ($(this).hasClass("rubber-band")) $(this).addClass('rubberBand');
				if ($(this).hasClass("fade-in-down")) $(this).addClass('fadeInDown');
				if ($(this).hasClass("fade-in-left")) $(this).addClass('fadeInLeft');
				if ($(this).hasClass("fade-in-right")) $(this).addClass('fadeInRight');
				if ($(this).hasClass("rotate-in")) $(this).addClass('rotateIn');
			} else {

				// if( $(this).hasClass('fadeInDown') ) $(this).removeClass(' fadeInDown');
				// if( $(this).hasClass('fadeInLeft') ) $(this).removeClass('fadeInLeft');
				// if( $(this).hasClass('fadeInRight') ) $(this).removeClass(' fadeInRight');
				if ($(this).hasClass('Pulse')) $(this).removeClass('ani-view pulse Pulse');
				if ($(this).hasClass('rubberBand')) $(this).removeClass('ani-view rubber-band rubberBand');
				if ($(this).hasClass('fadeInDown')) $(this).removeClass('ani-view fade-in-down fadeInDown');
				if ($(this).hasClass('fadeInLeft')) $(this).removeClass('ani-view fade-in-left fadeInLeft');
				if ($(this).hasClass('fadeInRight')) $(this).removeClass('ani-view fade-in-right fadeInRight');
				if ($(this).hasClass("rotateIn")) $(this).addClass('ani-view rotate-in rotateIn');
			}
		});
	});
});