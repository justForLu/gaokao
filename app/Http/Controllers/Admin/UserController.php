<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Enums\BeanEnum;
use App\Enums\BoolEnum;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Common\BeanLog;
use App\Models\Common\City;
use App\Models\Common\Device;
use App\Models\Common\Grade;
use App\Repositories\Admin\UserRepository as User;
use App\Repositories\Admin\CityRepository;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = ['parent'=>0];
        $province = $this->city->getCityList($where);
        return view('admin.user.index',compact('province'));
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
            //推荐人
            $parent_ids = array_diff(array_unique(array_column($list,'parent_id')),[0]);
            $parent_list = [];
            if($parent_ids){
                $parent_list = \App\Models\Common\User::whereIn('id',$parent_ids)->pluck('username','id');
            }
            //会员等级
            $grade = Grade::pluck('name','id');
            foreach ($list as &$v){
                $v['status_name'] = BasicEnum::getDesc($v['status']);
                if($v['login_time']){
                    $v['login_time'] = date('Y-m-d H:i:s', $v['login_time']);
                }else{
                    $v['login_time'] = '';
                }
                $v['create_time'] = date('Y-m-d H:i:s', strtotime($v['create_time']));
                //推荐人
                $v['parent_name'] = $parent_list[$v['parent_id']] ?? '';
                //会员等级
                if($v['grade'] > 0){
                    $v['grade_name'] = $grade[$v['grade']] ?? '未知';
                }else{
                    $v['grade_name'] = '普通会员';
                }
                //直推数量
                $v['direct_num'] = \App\Models\Common\User::where('parent_id',$v['id'])->count();
                //团队数量
                $v['team_num'] = \App\Models\Common\User::where('invite_path','LIKE','%,'.$v['id'].',%')->count();

                unset($v['password']);
                unset($v['salt']);
                unset($v['pay_password']);
            }
        }
        return $this->ajaxData($list,$result['count'],'OK');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //会员等级
        $grade = Grade::select('name','id')->get();

        return view('admin.user.create',compact('grade'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(UserRequest $request)
    {
        $params = $request->all();
        $data = [
            'username' => $params['username'] ?? '',
            'mobile' => $params['mobile'] ?? '',
            'real_name' => $params['real_name'] ?? '',
            'nickname' => $params['nickname'] ?? '',
            'grade' => $params['grade'] ?? 0,
            'is_robot' => $params['is_robot'] ?? 0,
            'money' => 0,
            'bean' => 0,
            'status' => BasicEnum::ACTIVE,
            'create_time' => time()
        ];

        //密码和加密盐
        $salt = mt_rand(1000,9999);
        $data['password'] = md5($params['password'].$salt);
        $data['salt'] = $salt;

        //邀请码
        //生成随机邀请码
        $letter = ['A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','0',
            '1','2','3','4','5','6','7','8','9'];
        $arr = array_rand($letter,6);
        $code = $letter[$arr[0]].$letter[$arr[1]].$letter[$arr[2]].$letter[$arr[3]].$letter[$arr[4]].$letter[$arr[5]];
        while (\App\Models\Common\User::where('invite_code',$code)->count()){
            $arr = array_rand($letter,6);
            $code = $letter[$arr[0]].$letter[$arr[1]].$letter[$arr[2]].$letter[$arr[3]].$letter[$arr[4]].$letter[$arr[5]];
        }
        $data['invite_code'] = $code;

        $result = $this->user->create($data);

        return $this->ajaxAuto($result,'添加');
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
        //处理省市县
        $region_ids = [$data->province, $data->city, $data->area];
        $region_ids = array_diff(array_unique($region_ids),[0]);
        $region_list = [];
        if($region_ids){
            $region_list = City::whereIn('id', $region_ids)->pluck('title','id');
        }
        $province_name = $region_list[$data->province] ?? '';
        $city_name = $region_list[$data->city] ?? '';
        $area_name = $region_list[$data->area] ?? '';

        $data->province_city = $province_name.'-'.$city_name.'-'.$area_name;

        //处理登录时间
        if($data->login_time){
            $data->login_time = date('Y-m-d H:i:s', $data->login_time);
        }else{
            $data->login_time = '';
        }

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
        //会员等级
        $grade = Grade::select('name','id')->get();

        return view('admin.user.edit',compact('data','grade'));
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
            'grade' => $params['grade'] ?? 0,
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
        if(isset($params['real_name']) && !empty($params['real_name'])){
            $data['real_name'] = $params['real_name'];
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
        $this->log->writeOperateLog($request,'更改用户状态'); //日志记录

        return $this->ajaxAuto($result,'修改');
    }

    /**
     * 玉豆充值
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function recharge($id, Request $request)
    {
        $params = $request->all();

        if($request->isMethod('GET') && $id){
            $username = $params['username'] ?? '';

            return view('admin.user.recharge',compact('username'));
        }else{
            $bean = $params['bean'] ?? 0;
            if(!is_numeric($bean) || $bean <= 0){
                return $this->ajaxError('充值玉豆数量必须是大于0的数字');
            }
            //充值玉豆
            DB::beginTransaction();
            try{
                $res = \App\Models\Common\User::where('id',$id)->increment('bean', $bean);
                if($res != false){
                    //玉豆日志
                    $log = [
                        'user_id' => $id,
                        'bean' => $bean,
                        'type' => BeanEnum::RECHARGE,
                        'describe' => Auth::user()->username.'充值玉豆'.$bean,
                        'create_time' => time()
                    ];
                    $res2 = BeanLog::insert($log);
                    if($res2){
                        DB::commit();
                        return $this->ajaxSuccess(null,'充值成功');
                    }
                }
                DB::rollBack();
                return $this->ajaxError('充值失败');
            }catch (\Exception $e){
                DB::rollBack();
                return $this->ajaxError('充值失败');
            }
        }
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
        //如果是解冻，加一个解冻时间
        if($field == 'is_freeze' && $value == BoolEnum::NO){
            $data = [
                $field => $value,
                'thaw_time' => time(),
                'update_time' => time()
            ];
        }else{
            $data = [
                $field => $value,
                'update_time' => time()
            ];
        }

        $result = $this->user->update($data,$id);
        $this->log->writeOperateLog($request, '更新抢单');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
