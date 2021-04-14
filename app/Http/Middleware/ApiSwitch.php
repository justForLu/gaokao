<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ApiSwitch
{

    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //检查网站是否关闭
        $system_switch = Cache::get(Config::get('common.cache.website_config').'common_system_switch');
        if(empty($system_switch)){
            $config_info = \App\Models\Common\Config::where('only_tag','common_system_switch')->pluck('value');
            $system_switch = $config_info[0] ?? '';
            Cache::put(Config::get('common.cache.website_config').'common_system_switch', $system_switch, 1440);
        }
        if(!$system_switch){
            return response()->json(['status' => 'fail','code' => 300,'msg' => '网站已被关闭，请联系贾总。']);
        }
        return $next($request);
    }
}
