<?php

namespace App\Repositories\Api;


use App\Enums\BasicEnum;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Category';
    }

    /**
     * 列表
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getList($params = [], $field = '*')
    {
        $list = $this->model->select($field)->where('status',BasicEnum::ACTIVE)
            ->orderBy('sort', 'DESC')->get();

        return $list;
    }

}
