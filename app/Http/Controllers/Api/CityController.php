<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\CityRepository as City;
use Illuminate\Http\Request;

class CityController extends BaseController
{

    protected $city;

    public function __construct(City $city,Request $request)
    {
        parent::__construct($request);

        $this->city = $city;

    }

    /**
     * 获取城市列表
     * pid为0时表示获取省份，有值时获取该pid下的地区
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $list = $this->city->getList($params);

        return $this->returnSuccess($list,'OK');
    }

    /**
     * 根据城市ID，获取省份、城市、区县列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByCity(Request $request)
    {
        $params = $request->all();
        if(!isset($params['city_id']) || empty($params['city_id'])){
            return $this->returnError('确实城市ID');
        }

        $list = $this->city->getListByCity($params);

        return $this->returnSuccess($list,'OK');
    }
}




