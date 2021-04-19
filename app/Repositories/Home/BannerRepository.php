<?php

namespace App\Repositories\Home;

use App\Enums\BasicEnum;
use App\Repositories\BaseRepository;

class BannerRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Banner';
    }


    /**
     * 根据位置获取对应的幻灯片
     * @param array $where
     * @param int $limit
     * @return array
     */
    public function getList($where = [],$limit = 0)
    {
        $model = $this->model->where($where)
            ->where('status',BasicEnum::ACTIVE)
            ->orderBy('sort','DESC');

        if($limit){
            $model = $model->limit($limit);
        }

        $list = $model->get()->toArray();

        return $list;
    }
}
