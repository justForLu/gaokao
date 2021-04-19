<?php

namespace App\Repositories\Api;

use App\Enums\BasicEnum;
use App\Jobs\UserGrade;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Config;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\User';
    }

    /**
     * 登陆
     * @param array $params
     * @return mixed
     */
    public function login($params = [])
    {
        //判断用户名、密码是否填写
        if(!isset($params['username']) ||  empty($params['username'])){
            return $this->returnFail('请填写用户名');
        }
        if(!isset($params['password']) || empty($params['password'])){
            return $this->returnFail('请填写密码');
        }
        //判断用户是否存在
        $userInfo = $this->model->orWhere('username',$params['username'])->orWhere('mobile',$params['username'])->first();
        if(empty($userInfo)){
            return $this->returnFail('用户不存在');
        }
        //验证用户名和密码是否匹配
        if(md5($params['password'].$userInfo->salt) != $userInfo->password){
            return $this->returnFail('密码不正确');
        }

        //验证通过之后，更新用户登录信息，并生成token
        $login_ip = get_client_ip();
        $login_time = time();
        $login_times = $userInfo->login_times + 1;

        $token = md5($login_ip.$login_time.$userInfo->username.$userInfo->id);

        $data = [
            'login_ip' => $login_ip,
            'login_time' => $login_time,
            'login_times' => $login_times,
            'update_time' => time()
        ];

        $this->model->where('id', $userInfo->id)->update($data);

        $this->redis()->set(Config::get('api.user_token').$token, json_encode($userInfo));
        $this->redis()->expire(Config::get('api.user_token').$token,30*86400);
        $this->redis()->set(Config::get('api.user_id').$userInfo->id,$token, 30*86400);
        $this->redis()->expire(Config::get('api.user_id').$userInfo->id,30*86400);

        return $this->returnSuccess($token,'登录成功');
    }

    /**
     * 注册
     * @param array $params
     * @return array
     */
    public function register($params = [])
    {
        //验证提交过来的信息
        $msg = '';
        if(!isset($params['mobile']) || empty($params['mobile'])){
            $msg = '请输入手机号';
        }
        $check_mobile = check_mobile($params['mobile']);
        if(!$check_mobile){
            $msg = '手机号格式不正确';
        }
        if(!isset($params['password']) || empty($params['password'])){
            $msg = '请输入密码';
        }
        if(check_chinese($params['password'])){
            $msg = '密码不能包含汉字';
        }
        if(!isset($params['re_password']) || empty($params['re_password'])){
            $msg = '请再次输入密码';
        }
        if($params['password'] != $params['re_password']){
            $msg = '两次密码不一致';
        }
        if($msg){
            return $this->returnFail($msg);
        }

        //验证码是否过期
//        if(!$this->redis()->exists(Config::get('api.reg_code').$params['mobile'])){
//            return $this->returnFail('验证码已过期');
//        }
//        if($params['reg_code'] != $this->redis()->get(Config::get('api.reg_code').$params['mobile'])){
//            return $this->returnFail('验证码不正确');
//        }

        //手机号是否已经存在
        $is_exist = $this->model->orWhere('mobile',$params['mobile'])->orWhere('username',$params['mobile'])->count();
        if($is_exist > 0){
            return $this->returnFail('已存在的手机号');
        }

        //处理需要插入到用户表的数据
        $salt = mt_rand(1000,9999);
        $password = md5($params['password'].$salt);
        $data = [
            'username' => $params['mobile'],
            'password' => $password,
            'salt' => $salt,
            'mobile' => $params['mobile'],
            'money' => 0,
            'bean' => 0,
            'status' => BasicEnum::ACTIVE,
            'create_time' => time()
        ];
        //随机生成一个昵称
        $letter = ['A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','0',
            '1','2','3','4','5','6','7','8','9'];
        $arr = array_rand($letter,6);
        $rand_nickname = $letter[$arr[0]].$letter[$arr[1]].$letter[$arr[2]].$letter[$arr[3]].$letter[$arr[4]].$letter[$arr[5]];
        $data['nickname'] = $rand_nickname;

        //如果有邀请码，判断该邀请码的用户是否存在
        $invite_code = isset($params['invite_code']) && !empty($params['invite_code']) ? strtoupper($params['invite_code']) : '';    //邀请码
        if($invite_code){
            $parent = $this->model->where('invite_code',$invite_code)->first();
            if(empty($parent)){
                return $this->returnFail('邀请码有误或邀请人不存在');
            }
            $data['parent_id'] = $parent->id;
            if(empty($parent->invite_path)){
                $data['invite_path'] = ','.$parent->id.',';
            }else{
                $data['invite_path'] = $parent->invite_path.$parent->id.',';
            }
        }
        //插入用户数据
        $res = $this->model->insertGetId($data);
        if($res){
            //更改用户等级队列
            UserGrade::dispatch(['user_id' => $res])->onQueue('user_grade');
            return $this->returnSuccess(null,'注册成功');
        }

        return $this->returnFail('注册失败');
    }

    /**
     * 忘记密码--重置密码
     * @param array $params
     * @return array
     */
    public function forgetPwd($params = [])
    {
        //验证提交过来的信息
        $msg = '';
        if(!isset($params['mobile']) || empty($params['mobile'])){
            $msg = '请输入手机号';
        }
        if(!isset($params['forget_code']) || empty($params['forget_code'])){
            $msg = '请输入验证码';
        }
        if(!isset($params['password']) || empty($params['password'])){
            $msg = '请输入密码';
        }
        if(check_chinese($params['password'])){
            $msg = '密码不能包含汉字';
        }
        if(strlen($params['password']) < 6 || strlen($params['password']) > 16){
            $msg = '密码长度为6~16位';
        }
        //验证码是否过期
        if(!$this->redis()->exists(Config::get('api.forget_code').$params['mobile'])){
            $msg = '验证码已过期';
        }
        if($params['forget_code'] != $this->redis()->get(Config::get('api.forget_code').$params['mobile'])){
            $msg = '验证码不正确';
        }
        //查询用户是否存在
        $userInfo = $this->model->orWhere('username',$params['mobile'])->orWhere('mobile',$params['mobile'])->first();
        if(empty($userInfo)){
            $msg = '手机号或用户不存在';
        }
        if($msg){
            return $this->returnFail($msg);
        }

        $salt = mt_rand(1000,9999);
        $password = md5($params['password'].$salt);
        $data = [
            'password' => $password,
            'salt' => $salt,
            'update_time' => time(),
        ];

        $res = $this->model->where('id',$userInfo->id)->update($data);

        if($res){
            return $this->returnSuccess(null,'修改成功');
        }
        return $this->returnFail('修改失败');
    }

    /**
     * 修改登录密码
     * @param array $params
     * @return array
     */
    public function updatePwd($params = [])
    {
        $user_id = $params['user_id'] ?? 0;
        //验证提交过来的信息
        $msg = '';
        if(!isset($params['password']) || empty($params['password'])){
            $msg = '请输入密码';
        }
        if(!isset($params['confirm_password']) || empty($params['confirm_password'])){
            $msg = '请输入再次输入密码';
        }
        if(check_chinese($params['password'])){
            $msg = '密码不能包含汉字';
        }
        if(strlen($params['password']) < 6 || strlen($params['password']) > 16){
            $msg = '密码长度为6~16位';
        }
        if($params['password'] != $params['confirm_password']){
            $msg = '两次密码不一致';
        }
        if($msg){
            return $this->returnFail($msg);
        }

        $salt = mt_rand(1000,9999);
        $password = md5($params['password'].$salt);
        $data = [
            'password' => $password,
            'salt' => $salt,
            'update_time' => time(),
        ];

        $res = $this->model->where('id',$user_id)->update($data);

        if($res){
            return $this->returnSuccess(null,'修改成功');
        }
        return $this->returnFail('修改失败');
    }

    /**
     * 修改用户信息
     * @param array $params
     * @return array
     */
    public function updateUser($params = [])
    {
        $user_id = $params['user_id'] ?? 0;
        $data = [
            'update_time' => time(),
        ];
        //可以修改的数据
        if(isset($params['head_img']) && !empty($params['head_img'])){
            $data['head_img'] = $params['head_img'];
        }
        if(isset($params['nickname']) && !empty($params['nickname'])){
            $data['nickname'] = $params['nickname'];
        }
        if(isset($params['province']) && !empty($params['province'])){
            $data['province'] = $params['province'];
        }
        if(isset($params['city']) && !empty($params['city'])){
            $data['city'] = $params['city'];
        }
        if(isset($params['area']) && !empty($params['area'])){
            $data['area'] = $params['area'];
        }
        if(isset($params['address']) && !empty($params['address'])){
            $data['address'] = $params['address'];
        }
        if(isset($params['id_card']) && !empty($params['id_card'])){
            $data['id_card'] = $params['id_card'];
        }

        $res = $this->model->where('id',$user_id)->update($data);

        if($res){
            return $this->returnSuccess(null,'修改成功');
        }
        return $this->returnFail('修改失败');
    }

}
