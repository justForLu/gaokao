$(document).ready(function() {
	/*console.log("user.js");*/

	
	if(location.href.indexOf("leader.html") > 0){

	}
	
	if(location.href.indexOf("leader_form") > 0){
		var cmt_str_val = 0;
		var cmt_str_set = false;

		$("#cmt_str a").hover(function() {
			var $this = $(this);
			var val = $this.data('val');
			$("#cmt_str").attr('class', 'st'+val);
		},function(){
			if (cmt_str_set) {
				$("#cmt_str").attr('class', 'st'+cmt_str_val);
			}else{
				$("#cmt_str").attr('class', '');
			}
		});
		$("#cmt_str").on('click', 'a', function(event) {
			event.preventDefault();
			var $this = $(this);
			var val = $this.data('val');
			$("#cmt_str").attr('class', 'st'+val);
			cmt_str_val = val;
			cmt_str_set = true;
		});
	}
	if(location.href.indexOf("list_gz") > 0){
		$("#gz_list").on('click', '.show_more', function(event) {
		    event.preventDefault();
		    var $parent = $(this).parents("li");
		    $parent.toggleClass('active');
		});
	}
	if(location.href.indexOf("list_kq") > 0){
		$("#kq_list").on('click', '.show_more', function(event) {
		    event.preventDefault();
		    var $parent = $(this).parents("li");
		    $parent.toggleClass('active');
		});
	}

	if(location.href.indexOf("recommend") > 0){
		/*推荐*/
		var recom_layer;
		$(document).on('click', '#btn_recom_layer', function(event) {
		  event.preventDefault();
		    recom_layer = layer.open({
		      type: 1,
		      title:"推荐朋友",
		      area: ['800px', 'auto'],
		      content: $('#layer_recom'),
		      success: function(layero, index){
		      }
		    })
		});
		$("#layer_recom").on('click', '.btn_b', function(event) {
		  event.preventDefault();
	        /*推荐数据提交开始*/
	        $.ajax({
	    		type	: 'POST',	//请求方式
	    		url		: "/Public/recommend",	//请求连接
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
	}

	
});