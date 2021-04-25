<?php

namespace App\Repositories\Home;

use App\Repositories\BaseRepository;

class ArticleRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Article';
    }

    /**
     * 获取文章列表（有分页）
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getList($params = [], $field = '*')
    {
        $page = isset($params['page']) && $params['page'] > 0 ? $params['page'] : 1;
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;

        $where = [];
        if(isset($params['category_id']) && !empty($params['category_id'])){
            $where[] = ['category_id','=',$params['category_id']];
        }
        if(isset($params['is_recommend']) && !empty($params['is_recommend'])){
            $where[] = ['is_recommend','=',$params['is_recommend']];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy('is_top', 'DESC')
            ->orderBy('is_recommend', 'DESC')
            ->orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }
}
