<?php

namespace App\Repositories\Admin;

use App\Repositories\BaseRepository;

class ArticleRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Article';
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
        if(isset($params['title']) && !empty($params['title'])){
            $where[] = ['title','LIKE','%'.$params['title'].'%'];
        }
        if(isset($params['category_id']) && !empty($params['category_id'])){
            $where[] = ['category_id','=',$params['category_id']];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy('is_top', 'DESC')
            ->orderBy('is_recommend', 'DESC')
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get()->toArray();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 插入数据
     * @param array $data
     * @return mixed
     */
    public function createData($data = [])
    {
        $res = $this->model->insert($data);

        return $res;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateData($data = [],$id)
    {
        $res = $this->model->where('id',$id)->update($data);

        return $res;
    }

}
