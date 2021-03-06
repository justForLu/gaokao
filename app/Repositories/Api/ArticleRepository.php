<?php

namespace App\Repositories\Api;


use App\Enums\ArticleEnum;
use App\Enums\BasicEnum;
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
     * @throws \ReflectionException
     */
    public function getList($params = [], $field = '*')
    {
        $page = isset($params['page']) && $params['page'] > 0 ? $params['page'] : 1;
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;

        $where = [];
        $where[] = ['status','=',BasicEnum::ACTIVE];
        if(isset($params['type']) && !empty($params['type'])){
            $where[] = ['type','=',$params['type']];
        }

        $count = $this->model->where($where)->count();
        $page_total = ceil($count/$limit);

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy('sort', 'DESC')
            ->offset($offset)->limit($limit)->get();
        if($list){
            foreach ($list as &$v){
                $v['category_name'] = ArticleEnum::getDesc($v['category_id']);
            }
        }

        return ['list' => $list,'page_total' => $page_total];
    }

}
