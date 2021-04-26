<?php

namespace App\Repositories\Home;

use App\Repositories\BaseRepository;

class TagRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Tag';
    }

    /**
     * 获取标签列表
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getList($params = [], $field = '*')
    {
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;

        $where = [];
        if(isset($params['status']) && !empty($params['status'])){
            $where[] = ['status','=',$params['status']];
        }
        $offset = 0;
        $list = $this->model->select($field)->where($where)
            ->orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->offset($offset)->limit($limit)->get()->toArray();

        return $list;
    }
}
