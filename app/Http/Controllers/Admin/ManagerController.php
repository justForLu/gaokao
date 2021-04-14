<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Enums\BoolEnum;
use App\Enums\ModuleEnum;
use App\Http\Requests\Admin\ManagerRequest;
use App\Models\Admin\RoleUser;
use App\Repositories\Admin\Criteria\RoleCriteria;
use App\Repositories\Admin\ManagerRepository as Manager;
use App\Repositories\Admin\RoleRepository as Role;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagerController extends BaseController
{

    /**
     * @var Role
     */
    protected $role;

    /**
     * @var Manager
     */
    protected $manager;

    protected $log;

    public function __construct(Role $role,Manager $manager, LogRepository $log)
    {
        parent::__construct();

        $this->role = $role;
        $this->manager = $manager;
        $this->log = $log;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.manager.index');
    }

    /**
     * 管理员列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $params['with'] = ['roles'];
        $result = $this->manager->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v['role_name'] = isset($v->roles[0]['name']) ? $v->roles[0]['name'] : '';
                $v['status_name'] = BasicEnum::getDesc($v['status']);
                $v['is_system_val'] = BoolEnum::getDesc($v['is_system']);
            }
        }
        return $this->ajaxData($list,$result['count'],'OK');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function create()
    {
        $params = array();

        $this->role->pushCriteria(new RoleCriteria($params));
        $roleList = $this->role->all();
        return view('admin.manager.create',compact('roleList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ManagerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ManagerRequest $request)
    {
        $params = $request->filterAll();

        DB::beginTransaction();

        $data = [
            'username' => $params['username'] ?? '',
            'password' => Hash::make($params['password']),
            'mobile' => $params['mobile'] ?? '',
            'wechat' => $params['wechat'] ?? '',
            'status' => $params['status'] ?? 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];


        $res = $this->manager->create($data);

        if($res){
            $flag = RoleUser::create(array('user_id'=>$res['id'],'role_id'=>$params['role_id'],'module'=>ModuleEnum::ADMIN))->save();

            if($flag){
                DB::commit();
                $this->log->writeOperateLog($request,'添加管理员');  //日志记录
                return $this->ajaxSuccess(null,'添加成功');
            }else{
                DB::rollBack();
                return $this->ajaxError('添加失败');
            }
        }else{
            DB::rollBack();
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
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;

        if($this->currentUser->is_system) {
            $roleList = $this->role->all();
        } else {
            $params['parent'] = $this->currentUser->roles[0]->id;
            $this->role->pushCriteria(new RoleCriteria($params));
            $roleList = $this->role->findWhere(['is_system' => 0]);
        }
        $data = $this->manager->with(array('roles'))->find($id);

        return view('admin.manager.edit',compact('data','roleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ManagerRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ManagerRequest $request, $id)
    {
        $params = $request->filterAll();

        DB::beginTransaction();
        $role_id = $params['role_id'];unset($params['role_id']);

        $data = [
            'username' => $params['username'] ?? '',
            'mobile' => $params['mobile'] ?? '',
            'wechat' => $params['wechat'] ?? '',
            'status' => $params['status'] ?? 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if(isset($params['password']) && !empty($params['password'])){
            $data['password'] = Hash::make($params['password']);
        }
        $result = $this->manager->update($data,$id);

        if($result !== false){
            $roleUser = RoleUser::where('user_id',$id)->first();

            if($roleUser){
                $roleUser->role_id = $role_id;

                $flag = RoleUser::where('id',$roleUser['id'])->update($roleUser->toArray());

                if($flag !== false){
                    DB::commit();
                    $this->log->writeOperateLog($request,'更新管理员信息');    //日志记录
                    return $this->ajaxSuccess(null,'更新成功');
                }else{
                    DB::rollBack();
                    return $this->ajaxError('更新失败');
                }
            }else{
                DB::rollBack();
                return $this->ajaxError('更新失败');
            }
        }else{
            DB::rollBack();
            return $this->ajaxError('更新失败');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Request $request)
    {
        if($id == 1){
            return $this->ajaxError('超级管理员不能删除');
        }

        DB::beginTransaction();
        $result = $this->manager->delete($id);

        if($result){
            $flag = RoleUser::where('user_id',$id)->delete();

            if($flag !== false){
                DB::commit();
                return $this->ajaxSuccess(null,'删除成功');
            }else{
                DB::rollBack();
                return $this->ajaxError('删除失败');
            }
        }else{
            DB::rollBack();
            return $this->ajaxError('删除失败');
        }
    }

    /**
     * 修改资料
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @throws \ReflectionException
     */
    public function myInfo(Request $request)
    {
        if($request->isMethod('GET')){
            $userInfo = Auth::user();
            if($userInfo){
                $userInfo->status_name = BasicEnum::getDesc($userInfo->status);
            }
            return view('admin.manager.my_info', compact('userInfo'));
        }else{
            $params = $request->all();
            $userInfo = Auth::user();
            $data = [
                'mobile' => $params['mobile'] ?? '',
                'wechat' => $params['wechat'] ?? '',
                'update_time' => time()
            ];

            $res = $this->manager->update($data, $userInfo->id);
            if($res){
                return $this->ajaxSuccess(null,'修改成功');
            }
            return $this->ajaxError('修改失败');
        }
    }

    /**
     * 修改密码页面
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function myPwd(Request $request)
    {
        if($request->isMethod('GET')){

            return view('admin.manager.my_pwd');
        }else{
            $params = $request->all();
            $userInfo = Auth::user();
            //验证两次密码是否一致
            if($params['password'] != $params['repassword']){
                return $this->ajaxError('两次密码不一致');
            }
            //验证旧密码
            if(Hash::check($params['password'], $userInfo->password)){
                return $this->ajaxError('原始密码不正确');
            }
            //更改新密码
            $data = [
                'password' => Hash::make($params['password']),
                'update_time' => time()
            ];

            $res = $this->manager->update($data, $userInfo->id);
            if($res){
                return $this->ajaxSuccess(null,'修改密码成功');
            }
            return $this->ajaxError('修改密码失败');
        }
    }

}
