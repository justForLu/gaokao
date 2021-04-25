<?php

namespace App\Repositories\Home;

use App\Repositories\BaseRepository;

class BannerRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Banner';
    }

    /**
     * 根据位置获取对应的幻灯片
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getList($params = [],$field = '*')
    {
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;

        $where = [];
        if(isset($params['position']) && !empty($params['position'])){
            $where[] = ['position','=',$params['position']];
        }
        if(isset($params['terminal']) && !empty($params['terminal'])){
            $where[] = ['terminal','=',$params['terminal']];
        }
        $offset = 0;
        $list = $this->model->select($field)->where($where)
            ->orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->offset($offset)->limit($limit)->get()->toArray();

        return $list;
    }
}
