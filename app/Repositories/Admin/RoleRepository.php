<?php

namespace App\Repositories\Admin;


use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Admin\Role';
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
        $sortBy = isset($params['sortBy']) ? $params['sortBy'] : 'id';
        $sortType = isset($params['sortType']) ? $params['sortType'] : 'DESC';
        $with = isset($params['with']) && !empty($params['with']) ? $params['with'] : [];

        $where = [];
        if(isset($params['name']) && !empty($params['name'])){
            $where[] = ['name','LIKE','%'.$params['name'].'%'];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->with($with)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }

}
