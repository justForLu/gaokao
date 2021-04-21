<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Http\Controllers\Controller;
use App\Repositories\Home\CityRepository;
use App\Models\Common\City;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{

    protected $callback;
    protected $currentUser;
    protected $auth;
    protected $userInfo;

    /**
     * 父类构造器
     * BaseController constructor.
     */
    public function __construct(){
        $this->callback = isset($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : '';

        $this->middleware(function ($request, $next) {
            if(Auth::guard('home')->check()){
                $userInfo = Auth::guard('home')->user();
                $this->userInfo = $userInfo;
                view()->share('userInfo',$userInfo);
            }

            return $next($request);
        });

        view()->share('callback',$this->callback);
    }


    /**
     * 获取友情链接
     */
    public function getLinks()
    {

    }

    /**
     * 自动返回成功和失败内容
     * @param $flag
     * @param string $msg
     * @param string $referrer
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxAuto($flag,$msg = '操作',$referrer = '')
    {
        if($flag){
            return $this->ajaxSuccess(null,$msg.'成功',$referrer);
        }else{
            return $this->ajaxError($msg.'失败',$referrer);
        }
    }

    /**
     * ajax返回成功内容
     * @param null $data
     * @param string $msg
     * @param string $referrer
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxSuccess($data = null, $msg = 'ok',$referrer = '', $code = 200)
    {
        $ajaxData = array();

        $ajaxData['status'] = 'success';
        $ajaxData['msg'] = $msg;
        $ajaxData['data'] = $data;
        $ajaxData['code'] = $code;
        $ajaxData['referrer'] = $referrer;

        return response()->json($ajaxData);
    }

    /**
     * ajax返回失败内容
     * @param string $msg
     * @param string $referrer
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxError($msg = 'fail', $referrer = '', $code = 300)
    {
        $ajaxData = array();

        $ajaxData['status'] = 'fail';
        $ajaxData['msg'] = $msg;
        $ajaxData['code'] = $code;
        $ajaxData['referrer'] = $referrer;

        return response()->json($ajaxData);
    }
}
