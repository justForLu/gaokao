<?php

namespace App\Http\Controllers\Api;

use App\Enums\PositionEnum;
use App\Repositories\Api\BannerRepository as Banner;
use Illuminate\Http\Request;

class BannerController extends BaseController
{

    protected $banner;

    public function __construct(Banner $banner,Request $request)
    {
        parent::__construct($request);

        $this->banner = $banner;

    }

    /**
     * 获取轮播图列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $params = $request->all();
        $params['position'] = PositionEnum::INDEX;
        $list = $this->banner->getList($params);
        if($list){
            $http = get_http_type();
            foreach ($list as &$v){
                $v['image'] = $http.$_SERVER['HTTP_HOST'].$v['image'];
            }
        }

        return $this->returnSuccess($list,'OK');
    }

}




