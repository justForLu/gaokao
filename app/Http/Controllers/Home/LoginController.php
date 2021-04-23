<?php
namespace App\Http\Controllers\Home;

use App\Http\Requests\Home\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Home\UsersRepository;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    /**
     * 登录成功跳转
     * @var string
     */
    protected $redirectTo = '/home';
    /**
     * 退出跳转
     * @var string
     */
    protected $redirectAfterLogout = '/home';

    /**
     * 指定用户名字段
     * @var string
     */
    protected $username = 'username';

    /**
     * 指定guard
     * @var string
     */
    protected $guard = 'home';

    /**
     * @var
     */
    protected $user;

    protected $request;

    protected $auth;

    /**
     * LoginController constructor.
     *
     * LoginController constructor.
     * @param Request $request
     * @param UsersRepository $user
     */
    public function __construct(Request $request,UsersRepository $user)
    {
        $this->request = $request;
        $this->user = $user;
        $this->auth = Auth::guard('home');
        $this->middleware('guest')->except('logout');
    }

    /**
     * 登录视图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = '登录';
        return view('home.login.login',compact('title'));
    }

    /**
     * 登录校验处理(重写框架默认自带的登录校验)
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $loginRequest)
    {
        if ($this->attemptLogin($loginRequest)) {
            $this->updateLoginInfo($loginRequest);

            return response()->json(['status' => 'success','code' => '200','msg' => '登录成功','referrer' => $this->redirectTo]);
        }
        return response()->json(['status' => 'fail','code' => 300,'msg' => '登录失败，请检查用户名或密码']);
    }

    /**
     * 更新用户的登录信息
     * @param $loginRequest
     */
    public function updateLoginInfo($loginRequest)
    {
        $data['login_ip'] = $loginRequest->ip();
        $data['login_time'] = time();
        $data['login_times'] = $this->auth->user()->login_times + 1;
        $uid = $this->auth->user()->id;
        $this->user->update($data,$uid);
    }

    /**
     * 用户登出
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $this->auth->logout();

        return response()->json(['status' => 'success','code' => 0,'msg' => '退出成功','referrer' => $this->redirectAfterLogout]);
    }

    /**
     * 重写登陆方法，实现用户名、手机号、邮箱均可登陆
     * @param Request $request
     * @return bool
     */
    public function attemptLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // 验证用户名登录方式
        $usernameLogin = $this->auth->attempt(
            ['username' => $username, 'password' => $password], $request->filled('remember')
        );
        if ($usernameLogin) {
            return true;
        }
        //验证手机号登陆
        $mobileLogin = $this->auth->attempt(
            ['mobile' => $username, 'password' => $password], $request->filled('remember')
        );
        if ($mobileLogin) {
            return true;
        }

        // 验证邮箱登录方式
        $emailLogin = $this->auth->attempt(
            ['email' => $username, 'password' => $password], $request->filled('remember')
        );
        if ($emailLogin) {
            return true;
        }

        return false;
    }
}
