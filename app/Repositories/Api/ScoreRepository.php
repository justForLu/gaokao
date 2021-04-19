<?php

namespace App\Repositories\Api;

use App\Enums\ScienceEnum;
use App\Repositories\BaseRepository;

class ScoreRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Score';
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
        //文理科
        $science = isset($params['science']) && $params['science'] > 0 ? $params['science'] : ScienceEnum::SCIENCE;
        $where = [];
        if(isset($params['province']) && !empty($params['province'])){
            $where[] = ['province','=',$params['province']];
        }
        if(isset($params['year']) && !empty($params['year'])){
            $where[] = ['year','=',$params['year']];
        }

        $count = $this->model->where($where)->count();
        $page_total = ceil($count/$limit);

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->where($where)
            ->orderBy('year', 'DESC')
            ->offset($offset)->limit($limit)->get();
        if($list){
            foreach ($list as &$v){
                if($science == ScienceEnum::SCIENCE){
                    $v['yiben'] = $v['yiben_li'];
                    $v['erben'] = $v['erben_li'];
                    $v['sanben'] = $v['sanben_li'];
                    $v['dazhuan'] = $v['dazhuan_li'];
                }elseif ($science == ScienceEnum::LIBERAL){
                    $v['yiben'] = $v['yiben_wen'];
                    $v['erben'] = $v['erben_wen'];
                    $v['sanben'] = $v['sanben_wen'];
                    $v['dazhuan'] = $v['dazhuan_wen'];
                }
            }
        }

        return ['list' => $list,'page_total' => $page_total];
    }

}
