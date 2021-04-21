<?php

namespace App\Repositories\Admin;


use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Category';
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
        $sortBy = isset($params['sortBy']) ? $params['sortBy'] : 'type';
        $sortType = isset($params['sortType']) ? $params['sortType'] : 'DESC';

        $where = [];
        if(isset($params['name']) && !empty($params['name'])){
            $where[] = ['name','LIKE','%'.$params['name'].'%'];
        }
        if(isset($params['type']) && !empty($params['type'])){
            $where[] = ['type','=',$params['type']];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get()->toArray();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 根据条件获取分类全部列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function getAllList($where = [], $field = '*')
    {
        $list = $this->model->select($field)->where($where)
            ->orderBy('sort','DESC')
            ->orderBy('id','DESC')->get()->toArray();

        return $list;
    }
}
