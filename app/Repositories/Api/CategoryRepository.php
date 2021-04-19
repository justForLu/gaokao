<?php

namespace App\Repositories\Api;

use App\Enums\BasicEnum;
use App\Enums\CategoryEnum;
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
        $type = isset($params['type']) && $params['type'] > 0 ? $params['type'] : CategoryEnum::ARTICLE;
        $list = $this->model->select($field)->where('status',BasicEnum::ACTIVE)
            ->where('type',$type)
            ->orderBy('sort', 'DESC')->get();

        return $list;
    }

}
