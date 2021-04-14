<?php

namespace App\Repositories\Api;


use App\Enums\BasicEnum;
use App\Enums\PositionEnum;
use App\Repositories\BaseRepository;

class BannerRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Banner';
    }

    /**
     * 获取列表
     * @param array $params
     * @return array
     */
    public function getList($params = [])
    {
        $position = isset($params['position']) ? $params['position'] : PositionEnum::INDEX;

        $list = $this->model->select('title','image','url')->where('position', $position)
            ->where('status', BasicEnum::ACTIVE)
            ->orderBy('sort', 'DESC')->get();

        return $list;
    }

}
