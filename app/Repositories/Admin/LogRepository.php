<?php

namespace App\Repositories\Admin;


use App\Enums\BasicEnum;
use App\Enums\ModuleEnum;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class LogRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Admin\Log';
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
        $sortBy = isset($params['sortBy']) ? $params['sortBy'] : 'id';
        $sortType = isset($params['sortType']) ? $params['sortType'] : 'DESC';
        $with = isset($params['with']) && !empty($params['with']) ? $params['with'] : [];

        $where = [];
        if(isset($params['name']) && !empty($params['name'])){
            $where[] = ['name','LIKE','%'.$params['name'].'%'];
        }
        if(Auth::guard('admin')->user()->is_system != 1){
            $where[] = ['user_id','=',Auth::guard('admin')->user()->id];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->with($with)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 记录操作日志
     * @param $request
     * @param $content
     */
    public function writeOperateLog($request, $content = ''){
        // 记录post请求
        if($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')){
            $action = get_action_name();
            $params = $request->all();
            unset($params['_token']);unset($params['_method']);

            $log = array(
                'user_id' => Auth::guard('admin')->user()->id,
                'operate_module' => $action['controller'],
                'operate_action' => $action['method'],
                'operate_url' => $request->getRequestUri(),
                'content' => $content,
                'ip' => get_client_ip(),
                'module' => ModuleEnum::ADMIN,
                'create_time' => time()
            );

            $this->model->insert($log);
        }
    }
}
