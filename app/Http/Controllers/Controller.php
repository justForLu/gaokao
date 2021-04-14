<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
     * @param int $count
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxSuccess($data = null, $msg = 'ok',$referrer = '', $code = 0, $count = 0)
    {
        $ajaxData = array();

        $ajaxData['status'] = 'success';
        $ajaxData['msg'] = $msg;
        $ajaxData['data'] = $data;
        $ajaxData['code'] = $code;
        $ajaxData['referrer'] = $referrer;
        $ajaxData['count'] = $count;

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

    /**
     * ajax返回列表数据
     * @param null $data
     * @param int $count
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxData($data = null, $count = 0, $msg = 'ok')
    {
        $ajaxData = array();

        $ajaxData['status'] = 'success';
        $ajaxData['msg'] = $msg;
        $ajaxData['data'] = $data;
        $ajaxData['count'] = $count;
        $ajaxData['code'] = 0;

        return response()->json($ajaxData);
    }

    /**
     * redis连接
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function redis()
    {
        return app('redis.connection');
    }

}
