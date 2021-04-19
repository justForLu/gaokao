<?php
namespace App\Http\Controllers\Home;

use App\Repositories\Home\CollectRepository as Collect;
use Illuminate\Http\Request;

class CollectController extends BaseController
{
    protected $collect;

    public function __construct(Collect $collect)
    {
        parent::__construct();

        $this->collect = $collect;
    }


    /**
     * 收藏
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect(Request $request)
    {
        $params = $request->all();

        if(!$params['add_cal'] || !$params['type'] || !$params['user_id'] || !$params['id']){
            return $this->ajaxError("数据错误，请稍后再试");
        }

        if(isset($params['add_cal']) && $params['add_cal'] == 'add'){
            $data = [
                'user_id' => $params['user_id'] ?? 0,
                'type' => $params['type'] ?? 0,
                'create_time' => time()
            ];
            if($params['type'] == 1){
                $data['recruit_id'] = $params['id'] ?? 0;
            }elseif ($params['type'] == 2){
                $data['shop_id'] = $params['id'] ?? 0;
            }

            $result = $this->collect->create($data);

            return $this->ajaxAuto($result,'收藏');

        }elseif (isset($params['add_cal']) && $params['add_cal'] == 'cal'){
            $where['user_id'] = $params['user_id'] ?? 0;
            $where['type'] = $params['type'] ?? 0;
            if($params['type'] == 1){
                $where['recruit_id'] = $params['id'];
            }elseif ($params['type'] == 2){
                $where['shop_id'] = $params['id'];
            }

            $result = $this->collect->cancelCollect($where);

            return $this->ajaxAuto($result,'取消收藏');
        }
    }

}
