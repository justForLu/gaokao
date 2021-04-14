var flag = true;	//短信验证码标识

/* 发送短信验证码开始 */
$('.send-sms').click(function(){
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
	$.post($("#imgVerify").attr("data-ajax"), {verify:verify, flag:'regSms_'}, function(data){
		if(data.status != 1){
			layer.close(loadA);
			layer.tips(data.msg, '#inputVerify', {tips:[2, '#FF5722']});
			refreshVerify();
			return false;
		}

		//发送短信
		var url = sendBtn.attr('data-ajax');
		$.post(url, {mobile:mobile},function(data){
			layer.close(loadA);
			//console.log(data);
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
//注册操作
function reg(){
	var agree = $("input[name='agreement']").attr("checked");
	if(agree !== 'checked'){
		layer.alert('请先同意注册协议！', {title:'温馨提示'});
		return false;
	}

	var ele = $("#form");

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

	var password = ele.find("input[name='password']").val();
	if($.trim(password) == ''){
		layer.tips('请输入密码！', ele.find("input[name='password']"), {tips:[2, '#FF5722']});
		return false;
	}
	if(password.length < 6){
		layer.tips('密码长度至少为6位！', ele.find("input[name='password']"), {tips:[2, '#FF5722']});
		return false;
	}

	var repassword = ele.find("input[name='repassword']").val();
	if($.trim(repassword) == ''){
		layer.tips('请输入重复密码！', ele.find("input[name='repassword']"), {tips:[2, '#FF5722']});
		return false;
	}

	if(password != repassword){
		layer.alert('两次密码不一致！', {title:'温馨提示'});
		return false;
	}else{
		if(is_md5 == false){
			ele.find("input[name='password']").val(hex_md5(password));
			ele.find("input[name='repassword']").val(hex_md5(repassword));
			is_md5 = true;
		}
	}

	var loadA = layer.load(2);	//弹出加载框
	$.post(ele.attr("action"), ele.serialize(), function(data){
		layer.close(loadA);
		var tipAlert = layer.alert(data.msg, {title:'温馨提示'}, function(){
			if(data.status == 1){
				location.href = data.href;
			}else{
				ele.find("input[name='verify']").val('');
				ele.find("input[name='code']").val('');
				ele.find("input[name='password']").val('');
				ele.find("input[name='repassword']").val('');
				is_md5 = false;
			}
			layer.close(tipAlert);
		});
	}, 'json');
}

/* 是否同意注册协议 */
function agreeInput(){
	var flag = $("input[name='agreement']").attr("checked");
	if(flag === 'checked'){
		$("#regbtn").removeClass("reg_btn_f");
	}else{
		$("#regbtn").addClass('reg_btn_f');
	}
}

/* 查看注册协议 */
function agreement(href){
	var left = $(window).width();
	left = (left-960)/2;
	window.open(
		href,
		"",
		"toolbar=no,width=960,height=500,status=no,scrollbars=yes,resize=no,menuber=no,top=100,left="+left
	);
}