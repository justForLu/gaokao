<?php

namespace App\Http\Controllers\Api;

use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SmsController extends BaseController
{

    protected $sms;

    public function __construct(SmsService $sms, Request $request)
    {
        parent::__construct($request);

        $this->sms = $sms;

    }

    /**
     * 登录验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function loginCode(Request $request)
    {
//        $params = $request->all();
//
//        $res_check = $this->checkMobile($params);
//        if($res_check != 'OK'){
//            return $this->returnError($res_check);
//        }
//
//        $code = $this->createCode();
//
//        //把验证码存到Redis里，有效期10分钟
//        $this->redis->set(Config::get('api.login_code').$params['mobile'],$code);
//        $this->redis->expire(Config::get('api.login_code').$params['mobile'],600);
//
//        //发送验证码
//        $this->sms->sendCode($params['mobile'],['code' => $code]);
//
//        return $this->returnSuccess(null,'调用成功');
    }

    /**
     * 注册验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function registerCode(Request $request)
    {
        $params = $request->all();

        $res_check = $this->checkMobile($params);
        if($res_check != 'OK'){
            return $this->returnError($res_check);
        }

        $code = $this->createCode();

        //把验证码存到Redis里，有效期10分钟
        $this->redis->set(Config::get('api.reg_code').$params['mobile'],$code);
        $this->redis->expire(Config::get('api.reg_code').$params['mobile'],600);

        //发送验证码
        $this->sms->sendCode($params['mobile'],['code' => $code]);

        return $this->returnSuccess(null,'调用成功');
    }

    /**
     * 忘记密码验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function forgetPwdCode(Request $request)
    {
        $params = $request->all();

        $res_check = $this->checkMobile($params);
        if($res_check != 'OK'){
            return $this->returnError($res_check);
        }

        $code = $this->createCode();

        //把验证码存到Redis里，有效期10分钟
        $this->redis->set(Config::get('api.forget_code').$params['mobile'],$code);
        $this->redis->expire(Config::get('api.forget_code').$params['mobile'],600);

        //发送验证码
        $this->sms->sendCode($params['mobile'],['code' => $code]);

        return $this->returnSuccess(null,'调用成功');
    }

    /**
     * 修改密码验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function updatePwdCode(Request $request)
    {
        $params = $request->all();

        $res_check = $this->checkMobile($params);
        if($res_check != 'OK'){
            return $this->returnError($res_check);
        }
        //检查手机号是否与登录账号匹配
        if($params['mobile'] != $this->userInfo['mobile']){
            return $this->returnError('手机号与登录账号不匹配');
        }

        $code = $this->createCode();

        //把验证码存到Redis里，有效期10分钟
        $this->redis->set(Config::get('api.update_pwd_code').$params['mobile'],$code);
        $this->redis->expire(Config::get('api.update_pwd_code').$params['mobile'],600);

        //发送验证码
        $this->sms->sendCode($params['mobile'],['code' => $code]);

        return $this->returnSuccess(null,'调用成功');
    }

    /**
     * 验证手机号是否为空，格式是否正确
     * @param array $params
     * @return string
     */
    public function checkMobile($params = [])
    {
        if(!isset($params['mobile']) || empty($params['mobile'])){
            return '请填写手机号';
        }
        //验证手机号格式
        $check_mobile = check_mobile($params['mobile']);
        if(!$check_mobile){
            return '手机号格式不正确';
        }

        return 'OK';
    }

    /**
     * 生成验证码
     */
    public function createCode()
    {
        return mt_rand(100000,999999);
    }
}




