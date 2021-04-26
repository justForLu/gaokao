<?php

namespace App\Repositories\Home;

use App\Enums\BasicEnum;
use App\Repositories\BaseRepository;

class SchoolRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\School';
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
        if(isset($params['name']) && !empty($params['name'])){
            $where[] = ['name','LIKE','%'.$params['name'].'%'];
        }
        if(isset($params['province']) && !empty($params['province'])){
            $where[] = ['province','=',$params['province']];
        }
        if(isset($params['city']) && !empty($params['city'])){
            $where[] = ['city','=',$params['city']];
        }
        if(isset($params['area']) && !empty($params['area'])){
            $where[] = ['area','=',$params['area']];
        }
        if(isset($params['tag']) && !empty($params['tag'])){
            $params['tag'] = ','.$params['tag'].',';
            $where[] = ['tag','LIKE','%'.$params['tag'].'%'];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->offset($offset)->limit($limit)->get()->toArray();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 获取高校列表（无分页）
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getAllList($params = [], $field = '*')
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
