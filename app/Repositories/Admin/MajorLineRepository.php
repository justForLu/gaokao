<?php

namespace App\Repositories\Admin;

use App\Repositories\BaseRepository;

class MajorLineRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\MajorLine';
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
        if(isset($params['school_id']) && !empty($params['school_id'])){
            $where[] = ['school_id','=',$params['school_id']];
        }
        if(isset($params['major_id']) && !empty($params['major_id'])){
            $where[] = ['major_id','=',$params['major_id']];
        }
        if(isset($params['province']) && !empty($params['province'])){
            $where[] = ['province','=',$params['province']];
        }
        if(isset($params['year']) && !empty($params['year'])){
            $where[] = ['year','=',$params['year']];
        }
        if(isset($params['batch']) && !empty($params['batch'])){
            $where[] = ['batch','=',$params['batch']];
        }
        if(isset($params['science']) && !empty($params['science'])){
            $where[] = ['science','=',$params['science']];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->with($with)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get()->toArray();

        return ['list' => $list,'count' => $count];
    }

}
