<?php

namespace App\Repositories\Admin;

use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\User';
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

        $where = [];
        if(isset($params['username']) && !empty($params['username'])){
            $where[] = ['username','=',$params['username']];
        }
        if(isset($params['mobile']) && !empty($params['mobile'])){
            $where[] = ['mobile','=',$params['mobile']];
        }
        if(isset($params['email']) && !empty($params['email'])){
            $where[] = ['email','=',$params['email']];
        }
        if(isset($params['nickname']) && !empty($params['nickname'])){
            $where[] = ['nickname','=',$params['nickname']];
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
        if(isset($params['status']) && !empty($params['status'])){
            $where[] = ['status','=',$params['status']];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get()->toArray();

        return ['list' => $list,'count' => $count];
    }
}
