<?php

namespace App\Repositories\Admin;

use App\Repositories\BaseRepository;

class CityRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\City';
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
        $sortType = isset($params['sortType']) ? $params['sortType'] : 'ASC';

        $where = [];
        if(isset($params['title']) && !empty($params['title'])){
            $where[] = ['title','LIKE','%'.$params['title'].'%'];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 获取城市列表
     * @param $params
     * @return mixed
     */
    public function getCityList($params){
        return $this->model->where($params)->orderBy('sort','DESC')->orderBy('id','ASC')->get()->toArray();
    }

    /**
     * 获取城市名称
     * @param $id
     * @return mixed
     */
    public function getCityName($id){
        return $this->model->where('id',$id)->pluck('title');
    }
}
