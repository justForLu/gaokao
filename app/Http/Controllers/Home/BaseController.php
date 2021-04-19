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

            //城市
            $province_arr = session('province_arr');
            $city_info = session('city_info');
            if(!$province_arr){
                $province = City::where('parent',0)
                    ->where('status', BasicEnum::ACTIVE)
                    ->orderBy('sort','DESC')
                    ->orderBy('id','ASC')
                    ->get()->toArray();

                $province_arr = $province;
                session(['province_arr' => $province_arr]);
            }
            if(!$city_info){
                $city_info = ['id' => 148, 'province_id' => 10, 'title' => '郑州'];    //默认郑州

                session(['city_info' => $city_info]);
            }

            view()->share('province_arr', $province_arr);
            view()->share('city_info', $city_info);

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
        if($flag !== false){
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
