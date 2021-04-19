<?php

namespace App\Repositories\Home;

use App\Enums\BasicEnum;
use App\Repositories\BaseRepository;

class NewsRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\News';
    }


    /**
     * 获取新闻列表（无分页）
     * @param string $select
     * @param array $where
     * @param int $limit
     * @param array $with
     * @param array $order
     * @return array
     */
    public function getList($select = '*', $where = [], $limit = 0, $with = [], $order = [])
    {
        //处理一下默认排序
        if(empty($order)){
            $order = array(
                ['is_top','DESC'],
                ['is_recommend','DESC'],
                ['sort','DESC']
            );
        }

        $list = $this->getLists($this->model, $select, $where , $limit, $with, $order);

        return $list;
    }
}
