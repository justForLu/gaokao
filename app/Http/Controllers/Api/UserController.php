<?php

namespace App\Http\Controllers\Api;

use App\Models\Common\City;
use App\Repositories\Api\UserRepository as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserController extends BaseController
{

    protected $user;

    public function __construct(User $user,Request $request)
    {
        parent::__construct($request);

        $this->user = $user;

    }

    /**
     * 获取用户信息
     */
    public function getUser()
    {
        $user_info = \App\Models\Common\User::where('id',$this->userInfo['id'])->first()->toArray();
        unset($user_info['password']);
        unset($user_info['salt']);
        unset($user_info['parent_id']);
        unset($user_info['invite_path']);
        unset($user_info['role']);
        $user_info['create_time'] = date('Y-m-d H:i:s', strtotime($user_info['create_time']));
        $user_info['login_time'] = date('Y-m-d H:i:s', $user_info['login_time']);
        $user_info['head_img_url'] = !empty($user_info['head_img']) ? get_http_type().$_SERVER['HTTP_HOST'].$user_info['head_img'] : get_http_type().$_SERVER['HTTP_HOST'].'/assets/api/images/head_img_default.png';
        $user_info['alipay_img_url'] = !empty($user_info['alipay_code']) ? get_http_type().$_SERVER['HTTP_HOST'].$user_info['alipay_code'] : '';
        $user_info['wechat_img_url'] = !empty($user_info['wechat_code']) ? get_http_type().$_SERVER['HTTP_HOST'].$user_info['wechat_code'] : '';
        //省市县
        $region_ids = array_diff([$user_info['province'],$user_info['city'],$user_info['area']],[0]);
        $city_list = [];
        if($region_ids){
            $city_list = City::whereIn('id',$region_ids)->pluck('title','id');
        }
        $province_name = $city_list[$user_info['province']] ?? '';
        $city_name = $city_list[$user_info['city']] ?? '';
        $area_name = $city_list[$user_info['area']] ?? '';
        $user_info['province_city'] = $province_name.'-'.$city_name.'-'.$area_name;
        $user_info['money'] = (double)$user_info['money'];
        $user_info['bean'] = (double)$user_info['bean'];

        return $this->returnSuccess($user_info,'OK');
    }

    /**
     * 找回密码--重置密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPwd(Request $request)
    {
        $params = $request->all();

        $res = $this->user->forgetPwd($params);

        if($res && $res['code'] == 200){
            return $this->returnSuccess(null,$res['msg']);
        }
        return $this->returnError($res['msg']);
    }

    /**
     * 修改登录密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePwd(Request $request)
    {
        $params = $request->all();
        $params['user_id'] = $this->userInfo['id'];

        $res = $this->user->updatePwd($params);

        if($res && $res['code'] == 200){
            return $this->returnSuccess(null,$res['msg']);
        }
        return $this->returnError($res['msg']);
    }

    /**
     * 修改支付密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePayPwd(Request $request)
    {
        $params = $request->all();
        if(!isset($params['mobile']) || empty($params['mobile'])){
            return $this->returnError('请输入手机号');
        }
        //检查手机号是否与登录账号匹配
        if($params['mobile'] != $this->userInfo['mobile']){
            return $this->returnError('手机号与登录账号不匹配');
        }

        $res = $this->user->updatePayPwd($params);

        if($res && $res['code'] == 200){
            return $this->returnSuccess(null,$res['msg']);
        }
        return $this->returnError($res['msg']);
    }

    /**
     * 修改用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request)
    {
        $params = $request->all();
        $params['user_id'] = $this->userInfo['id'];

        $res = $this->user->updateUser($params);

        if($res && $res['code'] == 200){
            //修改成功，更新Redis里的用户信息
            $user = $this->user->find($this->userInfo['id']);
            $this->redis->set(Config::get('api.user_token').$this->token,json_encode($user));
            $this->redis->expire(Config::get('api.user_token').$this->token,30*86400);

            return $this->returnSuccess(null,$res['msg']);
        }
        return $this->returnError($res['msg']);
    }

    /**
     * 获取我的团队
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeam(Request $request)
    {
        $params = $request->all();
        $params['user_id'] = $this->userInfo['id'];
        $data = $this->user->getTeam($params);

        return $this->returnSuccess($data,'OK');
    }

    /**
     * 获取我的邀请码
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInviteCode()
    {
        $params['user_id'] = $this->userInfo['id'];

        $res = $this->user->inviteCode($params);
        if($res && $res['code'] == 200){
            return $this->returnSuccess($res['data'],'OK');
        }

        return $this->returnError($res['msg']);
    }

    /**
     * 获取我的收益
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function getProfit(Request $request)
    {
        $params = $request->all();
        $params['user_id'] = $this->userInfo['id'];

        $result = $this->user->getProfit($params);

        return $this->returnSuccess($result,'OK');
    }

}




