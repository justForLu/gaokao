<?php

namespace App\Repositories\Api;

use App\Enums\BasicEnum;
use App\Enums\PositionEnum;
use App\Enums\TermEnum;
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
        $terminal = isset($params['terminal']) ? $params['terminal'] : TermEnum::WAP;

        $list = $this->model->select('title','image','url')->where('position', $position)
            ->where('terminal',$terminal)
            ->where('status', BasicEnum::ACTIVE)
            ->orderBy('sort', 'DESC')->get();

        return $list;
    }

}
