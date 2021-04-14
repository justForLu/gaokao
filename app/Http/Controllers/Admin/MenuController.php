<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Http\Requests\Admin\MenuRequest;
use App\Repositories\Admin\Criteria\MenuCriteria;
use App\Repositories\Admin\MenuRepository as Menu;
use App\Repositories\Admin\LogRepository;
use App\Services\TreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MenuController extends BaseController
{
    /**
     * @var Menu
     */
    protected $menu;
    protected $log;

    public function __construct(Menu $menu,LogRepository $log)
    {
        parent::__construct();

        $this->menu = $menu;
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        return view('admin.menu.index');
    }

    /**
     * 菜单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->menu->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v['status_name'] = BasicEnum::getDesc($v['status']);
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
        $params['grade'] = array('<',2);

        $this->menu->pushCriteria(new MenuCriteria($params));
        $list = $this->menu->all();
        $list = $tree::makeTree($list);

        return view('admin.menu.create',compact('params','list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MenuRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MenuRequest $request)
    {
        $data = $request->filterAll();

        $data = $this->menu->create($data);

        if($data){
            if($data->parent == 0){
                $data['path'] = '0,'. $data->id;
                $data['grade'] = 1;
            }else{
                $parentData = $this->menu->find($data->parent );
                $data['path'] = $parentData->path .','. $data->id;
                $data['grade'] = $parentData->grade + 1;
            }
            $flag = $this->menu->update($data->getAttributes(), $data->id);

            if($flag){
                $this->log->writeOperateLog($request,'添加菜单');   //日志记录
                return $this->ajaxSuccess(null,'添加成功',url('admin/menu'));
            }else{
                return $this->ajaxError('添加失败');
            }
        }else{
            return $this->ajaxError('添加失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @param TreeService $tree
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request,TreeService $tree)
    {
        $params = $request->all();
        $params['id'] = $id;

        $data = $this->menu->find($id);

        $params['module'] = $data->module;
        $params['grade'] = array('<',3);

        $this->menu->pushCriteria(new MenuCriteria($params));
        $list = $this->menu->all();

        $list = $tree::makeTree($list);
        return view('admin.menu.edit',compact('data','params','list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MenuRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MenuRequest $request, $id)
    {
        $data = $request->filterAll();
        if(!empty($data['parent'])){
            $parentData = $this->menu->find($data['parent']);

            if(!empty($parentData)){
                $data['path'] = $parentData->path . ',' . $id;
            }
        }

        $result = $this->menu->update($data,$id);

        // 更新用户缓存菜单
        if($result){
            // 获取用户菜单
            $uid = Auth::guard('admin')->user()->id;
            $userMenus = $this->menu->getUserMenuTree();

            // 缓存用户菜单
            Cache::store('file')->forever('menu_user_' . $uid,json_encode($userMenus));

            $this->log->writeOperateLog($request, '更新菜单');  //日志记录
        }
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
        $result = $this->menu->delete($id);

        return $this->ajaxAuto($result,'删除');
    }

}
