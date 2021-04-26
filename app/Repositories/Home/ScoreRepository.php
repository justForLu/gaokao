<?php

namespace App\Repositories\Home;

use App\Models\Common\City;
use App\Repositories\BaseRepository;

class ScoreRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Score';
    }

    /**
     * 获取历年分数线
     * @param array $params
     * @param string $field
     * @return array
     */
    public function getList($params = [], $field = '*')
    {
        $science = $params['science'] ?? 1; //默认理科
        $where = [];
        if(isset($params['province']) && !empty($params['province'])){
            $where[] = ['province','=',$params['province']];
        }

        $list = $this->model->select($field)->where($where)
            ->orderBy('year', 'DESC')->get()->toArray();
        if($list){
            //省份
            $province_id = array_diff(array_unique(array_column($list,'province')),[0]);
            $province_list = [];
            if($province_id){
                $province_list = City::whereIn('id',$province_id)->pluck('title','id');
            }
            foreach ($list as &$v){
                $v['province_name'] = $province_list[$v['province']] ?? '-';
                if($science == 1){
                    $v['yiben'] = $v['yiben_li'];
                    $v['erben'] = $v['erben_li'];
                    $v['sanben'] = $v['sanben_li'];
                    $v['dazhuan'] = $v['dazhuan_li'];
                    $v['science_name'] = '理科';
                }else{
                    $v['yiben'] = $v['yiben_wen'];
                    $v['erben'] = $v['erben_wen'];
                    $v['sanben'] = $v['sanben_wen'];
                    $v['dazhuan'] = $v['dazhuan_wen'];
                    $v['science_name'] = '文科';
                }
            }
        }

        return $list;
    }
}
