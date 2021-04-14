var flag = true;	//短信验证码标识

//验证身份证
function isCardNo(card) {
 var pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
 return pattern.test(card);
}

/* 发送短信验证码开始 */
$('.send-sms').click(function(){
	//var e = window.event || e;
    //    e.preventDefault();

	var sendBtn = $(this);
	if(flag == false){
		return false;
	}
	var mobile = $("#inputMobile").val();
	if(!mobile){
		layer.tips('请输入手机号', '#inputMobile', {tips:[2, '#FF5722']});
		return false;
	}
	if(checkTel(mobile) == false){
		layer.tips('手机号格式错误', '#inputMobile', {tips:[2, '#FF5722']});
		return false;
	}

	var verify = $("#inputVerify").val();
	if(!verify){
		layer.tips('请输入图形验证码', '#inputVerify', {tips:[2, '#FF5722']});
		return false;
	}

	//检测验证码
	var loadA = layer.load(2);	//弹出加载框
	$.post($("#imgVerify").attr("data-ajax"), {verify:verify, flag:'loginSms_'}, function(data){
		if(data.status != 1){
			layer.close(loadA);
			layer.tips(data.msg, '#inputVerify', {tips:[2, '#FF5722']});
			refreshVerify();
			return false;
		}

		//发送短信
		var url = sendBtn.attr('data-ajax');
		$.post(url, {mobile:mobile},function(data){
			//console.log(data);
			layer.close(loadA);
			switch(data.status){
				case 1:
					flag = false;
					unclick(sendBtn, 120);
					layer.alert("验证码短信发送成功，请注意查收！", {title:'温馨提示'});
					break;
				case -1:
					layer.tips(data.msg, '#inputMobile', {tips:[2, '#FF5722']});
					break;
				default:
					layer.alert(data.msg, {title:'温馨提示'});
					break;
			}
		}, 'json')
	}, 'json');
});

var is_md5 = false;
//登录操作
function login(type){
	var ele = $("#form"+type);

	//账号密码登录检测
	if(type == 1){
		var log_name = ele.find("input[name='log_name']").val();
		if(!log_name){
			layer.tips('请输入手机号或身份证号！', ele.find("input[name='log_name']"), {tips:[2, '#FF5722']});
			return false;
		}
		if(checkTel(log_name) == false && isCardNo(log_name)==false){
			layer.tips('请填写有效的手机号或身份证号！', ele.find("input[name='log_name']"), {tips:[2, '#FF5722']});
			return false;
		}

		var password = ele.find("input[name='password']").val();
		if($.trim(password) == ''){
			layer.tips('请输入密码', ele.find("input[name='password']"), {tips:[2, '#FF5722']});
			return false;
		}
		if(is_md5 == false){
			password = hex_md5(password);
			ele.find("input[name='password']").val(password);
			is_md5 = true;
		}
	}

	//手机号快速登录检测
	if(type == 2){
		var mobile = ele.find("input[name='mobile']").val();
		if(!mobile){
			layer.tips('请输入手机号！', ele.find("input[name='mobile']"), {tips:[2, '#FF5722']});
			return false;
		}
		if(checkTel(mobile) == false){
			layer.tips('手机号格式错误！', ele.find("input[name='mobile']"), {tips:[2, '#FF5722']});
			return false;
		}

		var code = ele.find("input[name='code']").val();
		if($.trim(code) == ''){
			layer.tips('请输入手机验证码', ele.find("input[name='code']"), {tips:[2, '#FF5722']});
			return false;
		}
	}
	var loadA = layer.load(2);	//弹出加载框
	$.post(ele.attr("action"), ele.serialize(), function(data){
		layer.close(loadA);
		if(data.status == 1){
			location.href = data.href;
		}else{
			var tipAlert = layer.alert(data.msg, {title:'温馨提示'}, function(){
				if(data.href){
					location.href = data.href;
				}
				if(type == 1){
					is_md5 = false;
					ele.find("input[name='password']").val('');
				}
				layer.close(tipAlert);
			});
		}
	}, 'json');
	return true;
}