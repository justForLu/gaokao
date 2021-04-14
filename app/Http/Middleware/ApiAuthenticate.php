<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('token');
        //检查token信息
        if(empty($token)){
            $ajaxData['status'] = 'error';
            $ajaxData['msg'] = '请先登录';
            $ajaxData['code'] = 301;

            return response()->json($ajaxData);
        }else{
            $redis = app('redis.connection');
            //检查token是否存在和有效
            $userInfo = $redis->get(Config::get('api.user_token').$token);
            if(empty($userInfo)){
                $ajaxData['status'] = 'error';
                $ajaxData['msg'] = '请先登录';
                $ajaxData['code'] = 301;

                return response()->json($ajaxData);
            }
        }

        return $next($request);
    }

}
