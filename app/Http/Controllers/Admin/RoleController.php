<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Enums\BoolEnum;
use App\Enums\ModuleEnum;
use App\Http\Requests\Admin\AuthorityRequest;
use App\Http\Requests\Admin\RoleRequest;
use App\Repositories\Admin\PermissionRepository as Permission;
use App\Repositories\Admin\Criteria\MenuCriteria;
use App\Repositories\Admin\RoleRepository as Role;
use App\Repositories\Admin\MenuRepository as Menu;
use App\Repositories\Admin\PermissionRoleRepository as PermissionRole;
use App\Repositories\Admin\LogRepository;
use App\Services\TreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{
    /**
     * @var Role
     */
    protected $role;
    /**
     * @var Menu
     */
    protected $menu;
    /**
     * @var Permission
     */
    protected $permission;
    /**
     * @var PermissionRole
     */
    protected $permissionRole;

    protected $log;

    public function __construct(Role $role,Menu $menu,Permission $permission,PermissionRole $permissionRole,LogRepository $log)
    {
        parent::__construct();

        $this->role = $role;
        $this->menu = $menu;
        $this->permission = $permission;
        $this->permissionRole = $permissionRole;
        $this->log = $log;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('admin.role.index');
    }

    /**
     * 管角色列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->role->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v['is_system_val'] = BoolEnum::getDesc($v['is_system']);
            }
        }
        return $this->ajaxData($list,$result['count'],'OK');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleRequest $request)
    {
        $params = $request->filterAll();

        $data = [
            'name' => $params['name'] ?? '',
            'desc' => $params['desc'] ?? '',
            'status' => $params['status'] ?? BasicEnum::DISABLED,
            'create_time' => time()
        ];

        $result = $this->role->create($data);

        if($result){
            $this->log->writeOperateLog($request,'添加角色');   //日志记录
            return $this->ajaxSuccess(null,'添加成功');
        }

        return $this->ajaxError('添加失败');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        return view('admin.role.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;

        $data = $this->role->find($id);
        return view('admin.role.edit',compact('data','params'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RoleRequest $request, $id)
    {
        $params = $request->filterAll();

        $data = [
            'name' => $params['name'] ?? '',
            'desc' => $params['desc'] ?? '',
            'status' => $params['status'] ?? BasicEnum::DISABLED,
            'update_time' => time()
        ];
        $result = $this->role->update($data,$id);
        $this->log->writeOperateLog($request,'编辑角色');   //日志记录

        return $this->ajaxAuto($result,'修改');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if($id == 1){
            return $this->ajaxError('超级管理员角色不能被删除');
        }

        $result = $this->role->delete($id);

        return $this->ajaxAuto($result,'删除');
    }

    /**
     * @param AuthorityRequest $request
     * @param TreeService $tree
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function authority(AuthorityRequest $request,TreeService $tree,$id = 0)
    {
        $params = $request->filterAll();

        $role = $this->role->find(isset($params['role_id']) ? $params['role_id'] : $id);
        $params['permissions'] = isset($params['permissions']) ? $params['permissions'] : array();
        $rolePermissions = $this->permissionRole->getRolePermissions($role->id);

        if($request->isMethod('GET') && $id){
            // 视图页面
            $params['module'] = $role->module;
            $params['role_id'] = $id;
            $params['is_system'] = BoolEnum::NO;

            $permissions = $menu_ids = array();

            if(Auth::guard('admin')->user()->is_system == BoolEnum::NO){
                // 非系统角色只能分配当前角色所有的权限,找出登录用户的角色信息
                $roles = Auth::guard('admin')->user()->roles;

                foreach($roles as $role){
                    $permissions = array_merge($permissions,array_column($role->permissions->toArray(),'id'));
                    $permissions_list = \App\Models\Admin\Permission::select([
                        DB::raw('any_value('.DB::getConfig('prefix').'permission.`name`) as `name`'),
                        DB::raw('any_value('.DB::getConfig('prefix').'permission.`code`) as `code`'),
                        DB::raw('any_value('.DB::getConfig('prefix').'permission.menu_id) as menu_id'),
                        DB::raw('any_value('.DB::getConfig('prefix').'permission.module) as module'),
                        DB::raw('any_value('.DB::getConfig('prefix').'permission.is_system) as is_system'),
                        DB::raw('any_value('.DB::getConfig('prefix').'permission_role.role_id) as pivot_role_id'),
                        DB::raw('any_value('.DB::getConfig('prefix').'permission_role.permission_id) as pivot_permission_id')
                    ])->join('permission_role','permission.id','=','permission_role.permission_id')->where('permission.module',ModuleEnum::ADMIN)
                        ->get()->toArray();
                    $menu_ids = array_merge($menu_ids,array_column($permissions_list,'menu_id'));
                }

                $menu_ids = array_merge($menu_ids,array_column(\App\Models\Admin\Menu::select([
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.`name`) as `name`'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.`code`) as `code`'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.parent) as parent'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.path) as path'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.url) as url'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.grade) as grade'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.sort) as sort'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.status) as status'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.icon) as icon'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.module) as module'),
                    DB::raw('any_value('.DB::getConfig('prefix').'menu.is_system) as is_system'),
                ])->whereIn('id',$menu_ids)->groupBy('parent')->get()->toArray(),'parent'));
                $params['menu_ids'] = $menu_ids;
            }

            $this->menu->pushCriteria(new MenuCriteria($params));
            $menuList = $this->menu->all();

            // 勾选权限默认选中
            foreach($menuList as $key => $val){
                $menuPermission = $val->permissions;

                if(!empty($menuPermission)){
                    foreach($menuPermission as $itemKey => $itemVal){
                        if(Auth::guard('admin')->user()->is_system == BoolEnum::NO){
                            if(in_array($itemVal['id'],$permissions)){
                                if(in_array($itemVal['id'],$rolePermissions)){
                                    $menuList[$key]->permissions[$itemKey]['checked'] = "checked";
                                }else{
                                    $menuList[$key]->permissions[$itemKey]['checked'] = '';
                                }
                            }else{
                                unset($menuList[$key]->permissions[$itemKey]);
                            }
                        }else{
                            if(in_array($itemVal['id'],$rolePermissions)){
                                $menuList[$key]->permissions[$itemKey]['checked'] = "checked";
                            }else{
                                $menuList[$key]->permissions[$itemKey]['checked'] = '';
                            }
                        }
                    }

                    $flag = count(array_diff(array_column($menuPermission->toArray(),'id'),$rolePermissions));
                    $menuList[$key]['checked'] = $flag ? '' : "checked";

                }
            }

            $menuList = $tree::makeTree($menuList);

            return view('admin.role.authority',compact('role','menuList','params'));
        }else{
            // 操作页面
            $delRolePermissions = array_diff($rolePermissions,$params['permissions']);
            $addRolePermissions = array_diff($params['permissions'],$rolePermissions);

            $exception = DB::transaction(function() use($delRolePermissions,$addRolePermissions,$params,$role){
                // 删除旧的数据
                if(count($delRolePermissions)){
                    $this->permissionRole->delPermissionRoles($delRolePermissions,$role);
                }

                // 增加新的数据
                if(count($addRolePermissions)){
                    $this->permissionRole->addPermissionRoles($addRolePermissions,$role);
                }

            });

            $result = is_null($exception) ? true : false;
            if($result){
                return $this->ajaxAuto($result,'提交', url('admin/role'));
            }
            return $this->ajaxAuto($result,'提交');
        }
    }
}
