<?php

namespace App\Repositories\Api;

use App\Enums\BasicEnum;
use App\Repositories\BaseRepository;

class CityRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\City';
    }

    /**
     * 根据上级ID获取列表
     * @param array $params
     * @return array
     */
    public function getList($params = [])
    {
        $pid = $params['pid'] ?? 0;

        $list = $this->model->select('id','title')
            ->where('parent',$pid)
            ->where('status', BasicEnum::ACTIVE)
            ->orderBy('sort','DESC')
            ->get()->toArray();

        return $list;
    }

    /**
     * 根据城市，获取省份、城市、区县列表
     * @param array $params
     * @return array
     */
    public function getListByCity($params = [])
    {
        $city_id = $params['city_id'] ?? 0;
        $city = $this->model->where('id',$city_id)->first();

        if($city){
            $province_list = $this->model->select('id','title')->where('parent',0)->orderBy('sort','DESC')->get()->toArray();
            $city_list = $this->model->select('id','title')->where('parent',$city->parent)->orderBy('sort','DESC')->get()->toArray();
            $area_list = $this->model->select('id','title')->where('parent',$city->id)->orderBy('sort','DESC')->get()->toArray();

            return ['province' => $province_list,'city' => $city_list,'area' => $area_list];
        }

        return ['province' => [],'city' => [],'area' => []];
    }

}
