<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use App\Repositories\Admin\MenuRepository as Menu;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\ManagerRepository as Manager;
use App\Repositories\Admin\LogRepository;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /**
     * 登录成功跳转
     * @var string
     */
    protected $redirectTo = '/admin/index';
    /**
     * 退出跳转
     * @var string
     */
    protected $redirectAfterLogout = '/admin/login';

    /**
     * @var
     */
    protected $manager;

    /**
     * @var
     */
    protected $menu;

    protected $log;

    /**
     * LoginController constructor.
     * @param Manager $manager
     * @param Menu $menu
     */
    public function __construct(Manager $manager,Menu $menu, LogRepository $log)
    {
//        $this->middleware('guest')->except('logout');
        $this->manager = $manager;
        $this->menu = $menu;
        $this->log = $log;
    }

    /**
     * 登录视图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index()
    {
        return view('admin.login.index');
    }

    /**
     * 自定义guard
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * 登录校验处理(重写框架默认自带的登录校验)
     *
     * @param LoginRequest $loginRequest
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $loginRequest)
    {
        $credentials = $loginRequest->only($this->username(), 'password');

        if (Auth::attempt($credentials)) {
            // 认证通过...

            // 获取用户菜单
            $uid = $this->guard()->user()->id;

            $userMenus = $this->menu->getUserMenuTree();

            // 缓存用户菜单
            Cache::store('file')->forever('menu_user_' . $uid,json_encode($userMenus));
            $this->log->writeOperateLog($loginRequest, '登录后台');
            return response()->json(['status' => 'success','code' => 0,'msg' => '登录成功','referrer' => $this->redirectTo]);
        }
        return response()->json(['status' => 'fail','code' => 300,'msg' => '登录失败']);

    }

    /**
     * 更新用户的登录信息
     * @param $loginRequest
     */
    public function updateLoginInfo($loginRequest)
    {
        $data['last_ip'] = $loginRequest->ip();
        $data['gmt_last_login'] = get_date();
        $uid = $this->guard()->user()->id;
        $this->manager->update($data,$uid);
    }

    /**
     * 用户登出
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        return response()->json(['status' => 'success','code' => 0,'msg' => '退出成功','referrer' => $this->redirectAfterLogout]);
    }

    /**
     * 用户登录用户名
     * @return string
     */
    public function username()
    {
        return 'username';
    }


}
