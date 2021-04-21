<?php

namespace App\Repositories\Admin;

use App\Repositories\BaseRepository;

class MajorRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Major';
    }

    /**
     * 获取列表
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getList($params = [], $field = '*')
    {
        $page = isset($params['page']) && $params['page'] > 0 ? $params['page'] : 1;
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;
        $sortBy = isset($params['sortBy']) ? $params['sortBy'] : 'school_id';
        $sortType = isset($params['sortType']) ? $params['sortType'] : 'DESC';
        $with = isset($params['with']) ? $params['with'] : [];

        $where = [];
        if(isset($params['name']) && !empty($params['name'])){
            $where[] = ['name','LIKE','%'.$params['name'].'%'];
        }
        if(isset($params['school_id']) && !empty($params['school_id'])){
            $where[] = ['school_id','=',$params['school_id']];
        }
        if(isset($params['category_id']) && !empty($params['category_id'])){
            $where[] = ['category_id','=',$params['category_id']];
        }
        if(isset($params['type']) && !empty($params['type'])){
            $where[] = ['type','=',$params['type']];
        }
        if(isset($params['grade']) && !empty($params['grade'])){
            $where[] = ['grade','=',$params['grade']];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->with($with)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get()->toArray();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 获取专业列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function getAllList($where = [],$field = '*')
    {
        $list = $this->model->select($field)->where($where)->get()->toArray();

        return $list;
    }
}
