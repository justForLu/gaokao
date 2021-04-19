<?php

namespace App\Repositories\Api;

use App\Enums\BasicEnum;
use App\Repositories\BaseRepository;

class SchoolRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\School';
    }

    /**
     * 获取列表
     * @param array $params
     * @param string $field
     * @return array
     * @throws \ReflectionException
     */
    public function getList($params = [], $field = '*')
    {
        $page = isset($params['page']) && $params['page'] > 0 ? $params['page'] : 1;
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;

        $where = [];
        $where[] = ['status','=',BasicEnum::ACTIVE];
        if(isset($params['province']) && !empty($params['province'])){
            $where[] = ['province','=',$params['province']];
        }

        $count = $this->model->where($where)->count();
        $page_total = ceil($count/$limit);

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy('sort', 'DESC')
            ->offset($offset)->limit($limit)->get();
        if($list){
            foreach ($list as &$v){

            }
        }

        return ['list' => $list,'page_total' => $page_total];
    }

    /**
     * 获取详情
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        $data = $this->model->where('id',$id)->first();

        return $data;
    }
}
