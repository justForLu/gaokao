<?php
/**
 * Created by PhpStorm.
 * User: Yolanda
 * Date: 2017/10/12
 * Time: 9:42
 */

namespace App\Repositories;

use App\Enums\BasicEnum;
use Prettus\Repository\Eloquent\BaseRepository as Repository;

abstract class BaseRepository extends Repository
{
    /**
     * 批量插入数据
     * @param $data
     * @return mixed
     */
    public function insertBatch($data){
        return $this->model->insert($data);
    }

    /**
     * redis连接
     */
    public function redis()
    {
        $redis = app('redis.connection');

        return $redis;
    }


    /**
     * 专为前端部分获取list方法整合的方法
     * @param null $model
     * @param string $select
     * @param array $where
     * @param int $limit
     * @param array $with
     * @param array $order
     * @return array
     */
    public function getLists($model = null, $select = '*', $where = [], $limit = 0, $with = [], $order = [])
    {
        if($with){
            $model = $model->with($with);
        }
        $model = $model->select($select)->where('status',BasicEnum::ACTIVE);
        if($where){
            foreach ($where as $key => $val){
                if($val && is_array($val)){
                    foreach ($val as $k => $v){
                        if ($k == 'IN' && is_array($v)){
                            $model = $model->whereIn($key,$v);
                        }elseif ($k == 'NOTIN' && is_array($v)){
                            $model = $model->whereNOtIn($key,$v);
                        }elseif ($k == 'EQ'){
                            $model = $model->where($key,'=',$v);
                        }elseif ($k == 'NEQ'){
                            $model = $model->where($key,'<>',$v);
                        }elseif ($k == 'GT'){
                            $model = $model->where($key,'>',$v);
                        }elseif ($k == 'EGT'){
                            $model = $model->where($key,'>=',$v);
                        }elseif ($k == 'LT'){
                            $model = $model->where($key,'<',$v);
                        }elseif ($k == 'ELT'){
                            $model = $model->where($key,'<=',$v);
                        }elseif ($k == 'LIKE'){
                            $model = $model->where($key,'LIKE','%'.$v.'%');
                        }elseif ($k == 'BETWEEN' && is_array($v)){
                            $model = $model->whereBetween($key,$v);
                        }elseif ($k == 'NOTBETWEEN' && is_array($v)){
                            $model = $model->whereNotBetween($key,$v);
                        }elseif ($k == 'NULL'){
                            $model = $model->whereNull($key);
                        }elseif ($k == 'NOTNULL'){
                            $model = $model->whereNotNull($key);
                        }
                    }
                }
            }
        }
        if($order){
            foreach ($order as $v){
                $model = $model->orderBy($v[0],$v[1]);
            }
        }
        if($limit){
            $model = $model->limit($limit);
        }

        $list = $model->get()->toArray();

        return $list;
    }


    /**
     * 成功返回
     * @param null $data
     * @param string $msg
     * @param int $code
     * @return array
     */
    public function returnSuccess($data = null,$msg = '',$code = 200)
    {
        return ['data' => $data,'msg' => $msg, 'code' => $code];
    }

    /**
     * 失败返回
     * @param string $msg
     * @param int $code
     * @return array
     */
    public function returnFail($msg = '',$code = 300)
    {
        return ['msg' => $msg, 'code' => $code];
    }


}
