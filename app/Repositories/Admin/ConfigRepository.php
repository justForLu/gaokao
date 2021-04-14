<?php

namespace App\Repositories\Admin;


use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ConfigRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Config';
    }

    /**
     * 获取列表（配置管理专用）
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
        if(isset($params['name']) && !empty($params['name'])){
            $where[] = ['name','LIKE','%'.$params['name'].'%'];
        }
        $manager_name = Auth::guard('admin')->user()->username;
        if($manager_name != 'jiazong'){
            $where[] = ['only_tag','<>','common_system_switch'];
        }

        $count = $this->model->whereNotIn('only_tag',$params['rob_index'])->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->whereNotIn('only_tag',$params['rob_index'])->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 获取列表（抢单配置专用）
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getRobList($params = [], $field = '*')
    {
        $page = isset($params['page']) && $params['page'] > 0 ? $params['page'] : 1;
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;
        $sortBy = isset($params['sortBy']) ? $params['sortBy'] : 'id';
        $sortType = isset($params['sortType']) ? $params['sortType'] : 'DESC';

        $where = [];
        if(isset($params['name']) && !empty($params['name'])){
            $where[] = ['name','LIKE','%'.$params['name'].'%'];
        }

        $count = $this->model->whereIn('only_tag',$params['rob_index'])->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->whereIn('only_tag',$params['rob_index'])->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }

}
