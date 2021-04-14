<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Repositories\Api\UserRepository as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class LoginController extends BaseController
{

    protected $user;

    public function __construct(User $user,Request $request)
    {
        parent::__construct($request);

        $this->user = $user;

    }

    /**
     * 登录
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $params = $request->all();

        $res = $this->user->login($params);

        if($res && $res['code'] == 200){
            return $this->returnSuccess($res['data'],'登录成功');
        }

        return $this->returnError($res['msg']);

    }

    /**
     * 退出
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $token = $request->header('token');

        $res = $this->redis->del(Config::get('api.user_token').$token);

        return $this->returnAuto($res,'退出');
    }

    /**
     * 注册
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $params = $request->all();

        $res = $this->user->register($params);

        if($res && $res['code'] == 200){
            //注册成功，调用登录接口
            $data = [
                'username' => $params['mobile'],
                'password' => $params['password']
            ];

            $res_login = $this->user->login($data);
            if($res_login && $res_login['code'] == 200){
                return $this->returnSuccess($res_login['data'],'注册成功');
            }

            return $this->returnSuccess(null,'注册成功');
        }

        return $this->returnError($res['msg']);
    }

}




