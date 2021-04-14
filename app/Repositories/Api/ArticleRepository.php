<?php

namespace App\Repositories\Api;


use App\Enums\ArticleEnum;
use App\Enums\BasicEnum;
use App\Enums\CashStatusEnum;
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
                $v['content'] = htmlspecialchars_decode($v['content']);
                $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            }
        }

        return ['list' => $list,'page_total' => $page_total];
    }

    /**
     * 获取提现详情
     * @param int $id
     * @param int $user_id
     * @return array
     */
    public function getDetail($id = 0, $user_id = 0)
    {
        $res = $this->model->where('id',$id)->first();

        //判断user_id是否匹配，如果不匹配，返回空数组
        if($res && $res == $res->user_id){
            unset($res->admin_id);
            unset($res->remark);
            $res->create_time = date('Y-m-d H:i:s', $res->create_time);

            return $res;
        }

        return [];
    }

}
