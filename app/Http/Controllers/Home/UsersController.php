<?php
namespace App\Http\Controllers\Home;

use App\Enums\RecruitEnum;
use App\Repositories\Home\Criteria\CollectCriteria;
use App\Repositories\Home\UsersRepository as Users;
use App\Repositories\Home\CollectRepository as Collect;
use App\Repositories\Home\FactoryRepository as Factory;
use App\Repositories\Home\PositionRepository as Position;
use App\Repositories\Home\LabelRepository as Label;
use App\Http\Controllers\Admin\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class UsersController extends BaseController
{

    protected $users;
    protected $collect;
    protected $factory;
    protected $position;
    protected $label;

    protected $count_collect;

    public function __construct(Users $users, Collect $collect, Factory $factory, Position $position, Label $label)
    {
        parent::__construct();

        $this->users = $users;
        $this->collect = $collect;
        $this->factory = $factory;
        $this->position = $position;
        $this->label = $label;

    }


	public function index()
    {


        return view('home.user.index');
    }


    /**
     * 我的资料
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info()
    {
        $menu = 'info';
        $nickname = $this->userInfo->nickname;
        $count_collect = $this->collect->getCollectCount(['user_id' => $this->userInfo->id]);
        //年龄选择——年份（最小16岁，最大50岁）
        $year = [];
        for ($i = 16; $i <=50; $i ++){
            $year[] = date('Y',strtotime('- '.$i.' Year'));
        }
        //月份
        $month = ['01','02','03','04','05','06','07','08','09','10','11','12',];

        $city = CityController::getCityArrString($this->userInfo->area,'id');
        $birthday = $this->userInfo->birthday;
        $birth_arr = explode('-',$birthday);
        $birth_year = $birth_arr[0] ?? 0;
        $birth_month = $birth_arr[1] ?? 0;

        return view('home.user.info', compact('menu','count_collect','nickname','year','month','city','birth_year','birth_month'));
    }

    /**
     * 更改我的资料
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sub_info(Request $request)
    {

        $params = $request->all();
        $year = $params['year'] ?? '';
        $month = $params['month'] ?? '';

        $birthday = ($year.'-'.$month);

        $data = [
            'mobile' => $params['mobile'] ?? '',
            'realname' => $params['realname'] ?? '',
            'id_card' => $params['id_card'] ?? '',
            'sex' => $params['sex'] ?? 0,
            'birthday' => $birthday,
            'province' => $params['province'] ?? 0,
            'city' => $params['city'] ?? 0,
            'area' => $params['area'] ?? 0,
            'update_time' => time()
        ];

        $result = $this->users->update($data, $this->userInfo->id);

        return $this->ajaxAuto($result,'编辑',url('/home/user/info.html'));
    }

    /**
     * 我的收藏——
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collect_recruit(Request $request)
    {
        $menu = 'collect';
        $nickname = $this->userInfo->nickname;
        $count_collect = $this->collect->getCollectCount(['user_id' => $this->userInfo->id]);
        $type = 1;

        $params = $request->all();
        $params['type'] = 1;   //默认招工收藏
        $params['user_id'] = $this->userInfo->id;

        $this->collect->pushCriteria(new CollectCriteria($params));

        $list = $this->collect->with(array('recruit'))->paginate(Config::get('home.page_size',10));

        if($list){
            //岗位列表
            $where1['type'] = RecruitEnum::FACTORY;
            $position = $this->position->getList($where1);
            $position = set_index($position, 'id');

            //工厂标签列表
            $where2['type'] = RecruitEnum::FACTORY;
            $label = $this->label->getList($where2);
            $label = set_index($label,'id');

            foreach ($list as &$v){
                $factory_id = isset($v->recruit->factory_id) ? $v->recruit->factory_id : 0;
                $v->factory = $this->factory->getById($factory_id);
                //处理招聘岗位
                $position_ids = explode(',',$v->recruit['posts']);

                $posts_arr = [];
                if($position_ids){
                    foreach ($position_ids as $p){
                        if(isset($position[$p][0]['name'])){
                            $posts_arr[] = $position[$p][0]['name'];
                        }
                    }
                }
                $v->posts_arr = $posts_arr;
                //处理工厂标签
                $label_ids = explode(',',$v->factory['label']);
                $label_arr = [];
                if($label_ids){
                    foreach ($label_ids as $l){
                        if(isset($label[$l][0]['name'])){
                            $label_arr[] = $label[$l][0]['name'];
                        }
                    }
                }
                $v->label_arr = $label_arr;
            }
        }

        return view('home.user.collect_recruit',compact('menu','count_collect','nickname','type','list'));
    }

    /**
     * 我的收藏——门店收藏
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collect_shop(Request $request)
    {
        $menu = 'collect';
        $nickname = $this->userInfo->nickname;
        $count_collect = $this->collect->getCollectCount(['user_id' => $this->userInfo->id]);
        $type = 2;

        $params = $request->all();
        $params['type'] = 2;
        $params['user_id'] = $this->userInfo->id;

        $this->collect->pushCriteria(new CollectCriteria($params));

        $list = $this->collect->with(array('shop'))->paginate(Config::get('home.page_size',10));

        return view('home.user.collect_shop',compact('menu','count_collect','nickname','type','list'));
    }

    /**
     * 更新我的收藏
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sub_collect(Request $request)
    {


        return $this->ajaxAuto(1,'编辑',url('/home/user/collect.html'));
    }


    /**
     * 我的头像
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function portrait()
    {
        $menu = 'portrait';
        $nickname = $this->userInfo->nickname;
        $count_collect = $this->collect->getCollectCount(['user_id' => $this->userInfo->id]);
        $image = $this->userInfo->image;

        return view('home.user.portrait', compact('menu','count_collect','nickname','image'));
    }


    /**
     * 更新我的头像
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sub_portrait(Request $request)
    {
        $params = $request->all();

        $data = [
            'image' => $params['image']
        ];

        $result = $this->users->update($data, $this->userInfo->id);

        return $this->ajaxAuto($result,'编辑',url('/home/user/portrait.html'));
    }

    /**
     * 我的资料
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account()
    {
        $menu = 'account';
        $nickname = $this->userInfo->nickname;
        $count_collect = $this->collect->getCollectCount(['user_id' => $this->userInfo->id]);

        return view('home.user.account',compact('menu','count_collect','nickname'));
    }

    /**
     * 更新我的密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sub_account_pwd(Request $request)
    {
        $params = $request->all();
        //两次密码是否一致
        if($params['password'] != $params['password_confirmation']){
            return $this->ajaxError("两次密码不一致");
        }

        //验证原密码是否正确
        $old_pwd = Hash::make($params['old_password']);
        $new_pwd = Hash::make($params['password']);
        if($old_pwd != $this->userInfo->pwssword){
            return $this->ajaxError("原密码不正确");
        }

        //如果原密码正确，则修改密码
        $data = [
            'password' => $new_pwd
        ];

        $result = $this->users->update($data, $this->userInfo->id);

        return $this->ajaxAuto($result,'编辑',url('/home/user/account.html'));
    }

    /**
     * 更新我的手机号
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sub_account_mobile(Request $request)
    {
        $params = $request->all();

        //验证验证码是否正确
        $code = session('update_mobile');
        if($code != $params['code']){
            return $this->ajaxError("验证码不正确");
        }

        //如果验证码正确，则修改手机号
        $data = [
            'mobile' => $params['mobile']
        ];

        $result = $this->users->update($data, $this->userInfo->id);

        return $this->ajaxAuto($result,'编辑',url('/home/user/account.html'));
    }

    /**
     * 我的消息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function message()
    {
        $menu = 'portrait';
        $nickname = $this->userInfo->nickname;
        $count_collect = $this->collect->getCollectCount(['user_id' => $this->userInfo->id]);

        return view('home.user.message',compact('menu','count_collect','nickname'));
    }

    /**
     * 处理我的消息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sub_message()
    {


        return view('home.user.message');
    }

    /**
     * 我的提现
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cash_out()
    {


        return view('home.user.cash_out');
    }

    /**
     * 处理我的提现
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sub_cash_out(Request $request)
    {


        return view('home.user.cash_out');
    }


}
