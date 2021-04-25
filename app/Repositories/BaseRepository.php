<?php
/**
 * Created by PhpStorm.
 * User: Yolanda
 * Date: 2017/10/12
 * Time: 9:42
 */

namespace App\Repositories;

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
