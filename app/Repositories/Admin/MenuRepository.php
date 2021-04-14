<?php

namespace App\Repositories\Admin;


use App\Enums\BasicEnum;
use App\Enums\ModuleEnum;
use App\Models\Admin\Manager;
use App\Models\Admin\Menu;
use App\Repositories\Admin\Criteria\MenuCriteria;
use App\Repositories\BaseRepository;
use App\Services\TreeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MenuRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Admin\Menu';
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

        $count = $this->model->where($where)->count();

        $offset = ($page-1)*$limit;
        $list = $this->model->select($field)->with($with)->where($where)
            ->orderBy($sortBy, $sortType)
            ->offset($offset)->limit($limit)->get();

        return ['list' => $list,'count' => $count];
    }

    /**
     * 获取用户的菜单树
     * @return array
     */
    public function getUserMenuTree(){
        $menuTree = array();

        if(Auth::guard('admin')->user()->is_system){
            // 系统用户分配所有权限
            $params['module'] = ModuleEnum::ADMIN;
            $params['status'] = BasicEnum::ACTIVE;
            $menuList = $this->pushCriteria(new MenuCriteria($params))->all();

            $menuTree = TreeService::makeTree($menuList);
        }else{
            // 创建用户分配指定权限
            $roles = Auth::guard('admin')->user()->roles;

            if($roles){
                // 找出登录用户的所有权限id
                $permissions = $menu_ids = array();

                foreach($roles as $role){
                    $permissions = array_merge($permissions,array_column($role->permissions->toArray(),'id'));
                    $menu_ids = array_merge($menu_ids,array_column($role->permissions()->distinct('menu_id')->get()->toArray(),'menu_id'));
                }

                if(count($permissions) && count($menu_ids)){
                    $menu_paths = implode(',',array_column(Menu::whereIn('id',$menu_ids)->select('path')->get()->toArray(),'path'));
                    $menus = array_filter(explode(',', $menu_paths));

                    $menu_items = Menu::whereIn('id',$menus)->get();

                    $menuTree = TreeService::makeTree($menu_items);
                }
            }
        }

        return $menuTree;
    }

}
