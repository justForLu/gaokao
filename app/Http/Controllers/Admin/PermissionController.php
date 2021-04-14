<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ModuleEnum;
use App\Http\Requests\Admin\PermissionRequest;
use App\Repositories\Admin\Criteria\MenuCriteria;
use App\Repositories\Admin\MenuRepository as Menu;
use App\Repositories\Admin\PermissionRepository as Permission;
use App\Repositories\Admin\LogRepository;
use App\Services\TreeService;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
    /**
     * @var Menu
     */
    protected $menu;

    protected $permission;

    protected $log;

    public function __construct(Menu $menu,Permission $permission, LogRepository $log)
    {
        parent::__construct();

        $this->menu = $menu;
        $this->permission = $permission;
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
        return view('admin.permission.index');
    }

    /**
     * 权限列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $params['with'] = ['menu'];
        $result = $this->permission->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v['menu_name'] = isset($v->menu['name']) ? $v->menu['name'] : '';
            }
        }
        return $this->ajaxData($list,$result['count'],'OK');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param TreeService $tree
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,TreeService $tree)
    {
        $params = $request->all();
        $params['module'] = isset($params['module']) ? $params['module'] : ModuleEnum::ADMIN;

        $params['grade'] = array('<',3);

        $this->menu->pushCriteria(new MenuCriteria($params));
        $list = $this->menu->all();

        $list = $tree::makeTree($list);
        return view('admin.permission.create',compact('params','list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PermissionRequest $request)
    {
        $data = $request->filterAll();

        $data = $this->permission->create($data);

        if($data){
            $this->log->writeOperateLog($request,'添加权限');   //日志记录
            return $this->ajaxSuccess(null,'添加成功');
        }else{
            return $this->ajaxError('添加失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @param Request $request
     * @param TreeService $tree
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request,TreeService $tree)
    {
        $params = $request->all();
        $params['id'] = $id;

        $params['grade'] = array('<',3);

        $this->menu->pushCriteria(new MenuCriteria($params));
        $list = $this->menu->all();

        $list = $tree::makeTree($list);

        $data = $this->permission->find($id);
        return view('admin.permission.edit',compact('data','params','list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PermissionRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PermissionRequest $request, $id)
    {
        $result = $this->permission->update($request->filterAll(),$id);
        $this->log->writeOperateLog($request,'更改权限');   //日志记录
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
        $result = $this->permission->delete($id);

        return $this->ajaxAuto($result,'删除');
    }

}
