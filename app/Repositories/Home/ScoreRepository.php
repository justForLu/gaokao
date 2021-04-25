<?php

namespace App\Repositories\Home;

use App\Repositories\BaseRepository;

class ScoreRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Score';
    }

    /**
     * 获取高校列表（有分页）
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getList($params = [], $field = '*')
    {
        $page = isset($params['page']) && $params['page'] > 0 ? $params['page'] : 1;
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;

        $where = [];
        if(isset($params['province']) && !empty($params['province'])){
            $where[] = ['province','=',$params['province']];
        }
        if(isset($params['year']) && !empty($params['year'])){
            $where[] = ['year','=',$params['year']];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }
}
