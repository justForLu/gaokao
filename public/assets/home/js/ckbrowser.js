	/*
	 * 判断用户的浏览器信息
	 * 20161019 c.k
	 */
	var sUserAgent = navigator.userAgent.toLowerCase();
    var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
    var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
    var bIsMidp = sUserAgent.match(/midp/i) == "midp";
    var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
    var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
    var bIsAndroid = sUserAgent.match(/android/i) == "android";
    var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
    var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
    if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM){
    	var comp_list_reg = /Company/;
        var comp_info_reg = /Company\/(\d+)/;
        var store_list_reg = /Store/;
        var store_info_reg = /Store\/(\d+)/;
        var artical_list_reg = /News/;
        var artical_info_reg = /News\/detail_n\/(\d+)/;		//判断是资讯
        // var num = //;
        var url = location.href;

        var result_comp_list = url.match(comp_list_reg);
        var result_comp = url.match(comp_info_reg);
        var result_store_list = url.match(store_list_reg);
        var result_store = url.match(store_info_reg);
        var result_artical_list = url.match(artical_list_reg);
        var result_artical = url.match(artical_info_reg);

        var href_url = site_url;
        if(result_comp) {
        	href_url = href_url + result_comp[0];
        }else if(result_comp_list) {
        	href_url = href_url + result_comp_list[0];
        }else if(result_store){
        	href_url = href_url + result_store[0];
        }else if(result_store_list){
        	href_url = href_url + result_store_list[0];
        }else if(result_artical){
        	href_url = href_url + result_artical[0];
        }else if(result_artical_list){
        	href_url = href_url + result_artical_list[0];
        }
        var _search = location.search;
    	if(_search.indexOf("?") != -1){
    		href_url += _search.replace('.html', '');
    	}
//        console.log(href_url);
        window.location.href = href_url;
    }