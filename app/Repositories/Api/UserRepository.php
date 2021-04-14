<?php

namespace App\Repositories\Api;

use App\Enums\BasicEnum;
use App\Enums\BeanEnum;
use App\Jobs\UserGrade;
use App\Models\Common\BeanLog;
use App\Repositories\BaseRepository;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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

        //用户信息存到token里，同时清除该用户其他之前登陆的token
        $last_token = $this->redis()->get(Config::get('api.user_id').$userInfo->id);
        if($last_token){
            //解除单点登录限制
//            $this->redis()->del(Config::get('api.user_token').$last_token);
        }

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
     * 修改支付密码
     * @param array $params
     * @return array
     */
    public function updatePayPwd($params = [])
    {
        //验证提交过来的信息
        $msg = '';
        if(!isset($params['pay_pwd_code']) || empty($params['pay_pwd_code'])){
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
        if(!$this->redis()->exists(Config::get('api.pay_pwd_code').$params['mobile'])){
            $msg = '验证码已过期';
        }
        if($params['pay_pwd_code'] != $this->redis()->get(Config::get('api.pay_pwd_code').$params['mobile'])){
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

        $pay_password = md5($params['password']);
        $data = [
            'pay_password' => $pay_password,
            'update_time' => time(),
        ];

        $res = $this->model->where('id',$userInfo->id)->update($data);

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
        $user = $this->model->where('id',$user_id)->first();
        $data = [
            'update_time' => time(),
        ];
        //可以修改的数据
        if(isset($params['head_img']) && !empty($params['head_img'])){
            $data['head_img'] = $params['head_img'];
        }
        if(isset($params['real_name']) && !empty($params['real_name'])){
            $data['real_name'] = $params['real_name'];
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
        if(isset($params['alipay']) && !empty($params['alipay'])){
            $data['alipay'] = $params['alipay'];
        }
        if(isset($params['alipay_code']) && !empty($params['alipay_code'])){
            $data['alipay_code'] = $params['alipay_code'];
        }
        if(isset($params['wechat']) && !empty($params['wechat'])){
            $data['wechat'] = $params['wechat'];
        }
        if(isset($params['wechat_code']) && !empty($params['wechat_code'])){
            $data['wechat_code'] = $params['wechat_code'];
        }
        if(isset($params['bank_name']) && !empty($params['bank_name'])){
            $data['bank_name'] = $params['bank_name'];
        }
        if(isset($params['bank_name']) && !empty($params['bank_branch'])){
            $data['bank_branch'] = $params['bank_branch'];
        }
        if(isset($params['account_name']) && !empty($params['account_name'])){
            $data['account_name'] = $params['account_name'];
        }
        if(isset($params['account_no']) && !empty($params['account_no'])){
            $data['account_no'] = $params['account_no'];
        }
        //如果修改了支付宝、支付宝、银行卡信息，校验验证码
//        if(isset($data['alipay']) || isset($data['alipay_code']) || isset($data['wechat']) || isset($data['wechat_code']) ||
//            isset($data['bank_name']) || isset($data['bank_branch']) || isset($data['account_name']) || isset($data['account_no'])){
//            //验证手机号
//            if(!isset($params['mobile']) || empty($params['mobile'])){
//                return $this->returnFail('请输入手机号');
//            }
//            if($params['mobile'] != $user->mobile){
//                return $this->returnFail('手机号与用户信息不匹配');
//            }
//            //验证码是否过期
//            if(!$this->redis()->exists(Config::get('api.user_bank_code').$params['mobile'])){
//                return $this->returnFail('验证码已过期');
//            }
//            if($params['user_bank_code'] != $this->redis()->get(Config::get('api.user_bank_code').$params['mobile'])){
//                return $this->returnFail('验证码不正确');
//            }
//        }

        $res = $this->model->where('id',$user_id)->update($data);

        if($res){
            return $this->returnSuccess(null,'修改成功');
        }
        return $this->returnFail('修改失败');
    }

    /**
     * 获取我的团队
     * @param array $params
     * @return array
     */
    public function getTeam($params = [])
    {
        $page = isset($params['page']) && $params['page'] > 0 ? $params['page'] : 1;
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;
        $type = $params['type'] ?? 0;   //type=1表示我的直推，type=2表示我的团队
        $user_id = $params['user_id'] ?? 0;
        if(empty($user_id) || !in_array($type,[1,2])){
            return ['list' => [],'page_total' => 0,'total' => 0];
        }

        $where = [];
        if($type == 1){
            $where[] = ['parent_id','=',$user_id];
        }elseif ($type == 2){
            $where[] = ['invite_path','LIKE','%,'.$user_id.',%'];
        }

        $count = $this->model->where($where)->count();
        //直推数量
        $direct_num = 0;
        //团队数量
        $team_num = 0;
        if($type == 1){
            $direct_num = $count;
            $team_num = $this->model->where('invite_path','LIKE','%,'.$user_id.',%')->count();
        }elseif ($type == 2){
            $direct_num = $this->model->where('parent_id','=',$user_id)->count();
            $team_num = $count;
        }

        $page_total = ceil($count/$limit);
        //获取我的直推人数和信息
        $offset = ($page-1)*$limit;
        $list = $this->model->select('nickname','mobile','create_time')
            ->where($where)
            ->orderBy('id', 'DESC')
            ->offset($offset)->limit($limit)
            ->get()->toArray();
        if($list){
            foreach ($list as &$v){
                $v['create_time'] = date('Y-m-d H:i:s',strtotime($v['create_time']));
            }
        }

        return ['list' => $list,'page_total' => $page_total,'direct_num' => $direct_num,'team_num' => $team_num];
    }

    /**
     * 获取邀请码
     * @param array $params
     * @return array
     */
    public function inviteCode($params = [])
    {
        $user_id = $params['user_id'] ?? 0;
        if($user_id <= 0){
            return $this->returnFail('用户信息有误');
        }
        $user_info = $this->model->find($user_id);
        if(empty($user_info->invite_code)){
            //生成随机邀请码
            $letter = ['A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','0',
                '1','2','3','4','5','6','7','8','9'];
            $arr = array_rand($letter,6);
            $code = $letter[$arr[0]].$letter[$arr[1]].$letter[$arr[2]].$letter[$arr[3]].$letter[$arr[4]].$letter[$arr[5]];
            while ($this->model->where('invite_code',$code)->count()){
                $arr = array_rand($letter,6);
                $code = $letter[$arr[0]].$letter[$arr[1]].$letter[$arr[2]].$letter[$arr[3]].$letter[$arr[4]].$letter[$arr[5]];
            }
            $data = [
                'invite_code' => $code,
                'update_time' => time(),
            ];

            $res = $this->model->where('id',$user_id)->update($data);
            if(!$res){
                return $this->returnFail('获取失败');
            }
        }else{
            $code = $user_info->invite_code;
        }
        $code_url = 'http://page.xinqianhui.cn/#/pages/home/register?code='.$code;
        $qrCode = new QrCode($code_url);
        header('Content-Type: '.$qrCode->getContentType());
        $imgInfo = chunk_split(base64_encode($qrCode->writeString()));

        return $this->returnSuccess(['invite_code' => $code,'image' => $imgInfo],'OK');
    }

    /**
     * 获取我的收益
     * @param array $params
     * @return array
     * @throws \ReflectionException
     */
    public function getProfit($params = [])
    {
        $user_id = $params['user_id'] ?? 0;
        $page = isset($params['page']) && $params['page'] > 0 ? $params['page'] : 1;
        $limit = isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] : 10;
        if($user_id <= 0){
            return ['list' => [],'page_total' => 0,'total' => 0];
        }
        $count = BeanLog::where('user_id',$user_id)->where('type',BeanEnum::REBATE)->count();
        $page_total = ceil($count/$limit);
        //获取我的直推人数和信息
        $offset = ($page-1)*$limit;
        $list = BeanLog::select('money','type','describe','create_time')
            ->where('user_id',$user_id)
            ->where('type',BeanEnum::REBATE)
            ->orderBy('id', 'DESC')
            ->offset($offset)->limit($limit)
            ->get()->toArray();
        if($list){
            foreach ($list as &$v){
                $v['type_name'] = BeanEnum::getDesc($v['type']);
                $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            }
        }

        return ['list' => $list,'page_total' => $page_total];
    }
}
