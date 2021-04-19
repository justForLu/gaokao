<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\ConfigRepository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ConfigController extends BaseController
{

    protected $config;

    public function __construct(Config $config,Request $request)
    {
        parent::__construct($request);

        $this->config = $config;

    }


    /**
     * 获取注册隐私协议
     */
    public function getRegPrivacy()
    {
        //检查缓存是否存在，不存在则数据库查询
        $value = Cache::get(\Illuminate\Support\Facades\Config::get('common.cache.website_config').'home_privacy_agreement');
        if(empty($value)){
            $value = $this->config->getConfig('home_privacy_agreement');
            Cache::put(\Illuminate\Support\Facades\Config::get('common.cache.website_config').'home_privacy_agreement', $value, 1440);
        }

        return $this->returnSuccess($value,'OK');
    }

    /**
     * 获取ICP备案号
     */
    public function getIcp()
    {
        //检查缓存是否存在，不存在则数据库查询
        $value = Cache::get(\Illuminate\Support\Facades\Config::get('common.cache.website_config').'home_keep_on_record');
        if(empty($value)){
            $value = $this->config->getConfig('home_keep_on_record');
            Cache::put(\Illuminate\Support\Facades\Config::get('common.cache.website_config').'home_keep_on_record', $value, 1440);
        }

        return $this->returnSuccess($value,'OK');
    }

    /**
     * 获取客服
     */
    public function getCustomTel()
    {
        //检查缓存是否存在，不存在则数据库查询
        $value = Cache::get(\Illuminate\Support\Facades\Config::get('common.cache.website_config').'home_customer_service_tel');
        if(empty($value)){
            $value = $this->config->getConfig('home_customer_service_tel');
            Cache::put(\Illuminate\Support\Facades\Config::get('common.cache.website_config').'home_customer_service_tel', $value, 1440);
        }
        //客服二维码
        $service_code = Cache::get(\Illuminate\Support\Facades\Config::get('common.cache.website_config').'home_service_code');
        if(empty($service_code)){
            $service_code = $this->config->getConfig('home_service_code');
            Cache::put(\Illuminate\Support\Facades\Config::get('common.cache.website_config').'home_service_code', $service_code, 1440);
        }

        $data = [
            'service_phone' => $value,
            'service_code' => !empty($service_code) ? get_http_type().$_SERVER['HTTP_HOST'].$service_code : ''
        ];
        return $this->returnSuccess($data,'OK');
    }

}




