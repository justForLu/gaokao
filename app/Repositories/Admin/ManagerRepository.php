<?php

namespace App\Repositories\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManagerRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Admin\Manager';
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
        $where[] = ['username','<>','jiazong'];
        if(isset($params['name']) && !empty($params['name'])){
            $where[] = ['name','LIKE','%'.$params['name'].'%'];
        }
        if(Auth::guard('admin')->user()->is_system != 1){
            $where[] = ['id','=',Auth::guard('admin')->user()->id];
        }

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->with($with)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 检查用户权限
     * @param $user
     * @param $permission
     * @return bool
     */
    public function hasPermission($user,$permission){
        $roles = $user->roles;

        if($roles){
            foreach($roles as $role){
                $permissions = array_column($role->permissions->toArray(),'id');
                if(in_array($permission->id,$permissions)) return true;
            }
            return false;
        }else{
            return false;
        }
    }

    public function getListById($id = 0)
    {
        $list = [];
        if(is_array($id)){
            $list = $this->model->select('id','username')->whereIn('id', $id)
                ->get()->toArray();
        }elseif (is_numeric($id)){
            $list = $this->model->select('id','username')->where('id',$id)
                ->get()->toArray();
        }

        return $list;
    }

}
