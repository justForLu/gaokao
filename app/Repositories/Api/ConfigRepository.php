<?php

namespace App\Repositories\Api;


use App\Repositories\BaseRepository;

class ConfigRepository extends BaseRepository
{

    protected $rich_test = [
        'home_privacy_agreement',
        'home_platform_notice'
    ];

    public function model()
    {
        return 'App\Models\Common\Config';
    }

    /**
     * 获取配置信息
     * @param string $only_tag
     * @return array
     */
    public function getConfig($only_tag = '')
    {
        if(empty($only_tag)){
            return $this->returnFail('缺少唯一标识');
        }

        $value = $this->model->where('only_tag',$only_tag)->pluck('value');
        $value = $value[0] ?? '';

        if(in_array($only_tag,$this->rich_test)){
            $value = htmlspecialchars_decode($value);
        }

        return $value;
    }


}
