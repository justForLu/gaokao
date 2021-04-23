<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Http\Requests\Admin\UserRequest;
use App\Repositories\Admin\UserRepository as User;
use App\Repositories\Admin\CityRepository;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * @var User
     */
    protected $user;
    protected $city;
    protected $log;

    public function __construct(User $user, CityRepository $city, LogRepository $log)
    {
        parent::__construct();

        $this->user = $user;
        $this->city = $city;
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * 用户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->user->getList($params);
        $list = $result['list'] ?? [];

        if($list){
            foreach ($list as &$v){
                $v['login_time'] = $v['login_time'] > 0 ? date('Y-m-d H:i:s', $v['login_time']) : '-';

                unset($v['password']);
                unset($v['salt']);
                unset($v['openid']);
            }
        }
        return $this->ajaxData($list,$result['count'],'OK');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     */
    public function store(UserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $data = $this->user->find($id);

        //处理时间
        $data->login_time = $data->login_time > 0 ? date('Y-m-d H:i:s', $data->login_time) : '-';

        return view('admin.user.show', compact('data'));
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
        $data = $this->user->find($id);

        return view('admin.user.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, $id)
    {
        $params = $request->all();

        $data = [
            'update_time' => time(),
        ];
        if(isset($params['password']) && !empty($params['password'])){
            //密码和加密盐
            $salt = mt_rand(1000,9999);
            $data['password'] = md5($params['password'].$salt);
            $data['salt'] = $salt;
        }
        if(isset($params['mobile']) && !empty($params['mobile'])){
            $data['mobile'] = $params['mobile'];
        }
        if(isset($params['nickname']) && !empty($params['nickname'])){
            $data['nickname'] = $params['nickname'];
        }
        if(isset($params['status']) && !empty($params['status'])){
            $data['status'] = $params['status'];
        }

        $result = $this->user->update($data,$id);

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
        //
    }

    /**
     * 修改用户状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateStatus(Request $request)
    {
        $params = $request->all();
        $id = $params['id'] ?? 0;
        $status = $params['status'] ?? BasicEnum::ACTIVE;

        $data = [
            'status' => $status,
            'update_time' => time()
        ];

        $result = $this->user->update($data,$id);
        $this->log->writeOperateLog($request,'更改用户信息'); //日志记录

        return $this->ajaxAuto($result,'修改');
    }

    /**
     * 表格单元格修改数据专用
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function changeValue(Request $request)
    {
        $params = $request->all();
        $id = $params['id'] ?? 0;
        $field = $params['field'] ?? '';
        if(!isset($params['value'])){
            return $this->ajaxError('未知错误，请联系管理员');
        }
        $value = $params['value'] ?? '';
        if(empty($id) || empty($field)){
            return $this->ajaxError('未知错误，请联系管理员');
        }
        $data = [
            $field => $value,
            'update_time' => time()
        ];

        $result = $this->user->update($data,$id);
        $this->log->writeOperateLog($request, '修改用户信息');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
