<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class BaseController extends Controller
{

    protected $userInfo;
    protected $redis;
    protected $token;

    /**
     * 父类构造器(接口请求拦截)
     * BaseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request){
        $this->redis = $this->redis();
        $this->token = $request->header('token');
        //获取用户信息
        if($this->token){
            $user = $this->redis->get(Config::get('api.user_token').$this->token);
            if(!empty($user)){
                $this->userInfo = json_decode($user,true);
            }else{
                $this->userInfo = [];
            }
        }
    }

    /**
     * 检查用户信息是否完善
     */
    public function checkUserInfo(){
        //
    }

    /**
     * 自动返回成功和失败内容
     * @param $flag
     * @param string $msg
     * @param string $referrer
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnAuto($flag,$msg = '操作')
    {
        if($flag !== false){
            return $this->returnSuccess(null,$msg.'成功');
        }else{
            return $this->returnError($msg.'失败');
        }
    }

    /**
     * ajax返回成功内容
     * @param null $data
     * @param string $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnSuccess($data = null, $msg = 'ok', $code = 200)
    {
        $ajaxData = array();

        $ajaxData['status'] = 'success';
        $ajaxData['msg'] = $msg;
        $ajaxData['data'] = $data;
        $ajaxData['code'] = $code;

//        header("Access-Control-Allow-Origin: *");
        return response()->json($ajaxData);
    }

    /**
     * ajax返回失败内容
     * @param string $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnError($msg = 'fail', $code = 300)
    {
        $ajaxData = array();

        $ajaxData['status'] = 'fail';
        $ajaxData['msg'] = $msg;
        $ajaxData['code'] = $code;

//        header("Access-Control-Allow-Origin: *");
        return response()->json($ajaxData);
    }

}
