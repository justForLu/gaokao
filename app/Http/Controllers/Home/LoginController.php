<?php
namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Home\UsersRepository;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    use AuthenticatesUsers;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);
    }

    /**
     * 登录校验处理(重写框架默认自带的登录校验)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->auth->attemptLogin($request)) {
            $this->updateLoginInfo($request);
            session(['userInfo' => $this->auth->user()]);

            return response()->json(['status' => 'success','code' => '200','msg' => Lang::get('auth.success'),'referrer' => $this->redirectPath()]);

        }

        return response()->json(['status' => 'fail','code' => '300','msg' => Lang::get('auth.failed')]);
    }

    /**
     * 更新用户的登录信息
     * @param $loginRequest
     */
    public function updateLoginInfo($loginRequest)
    {
        $data['login_ip'] = $loginRequest->ip();
        $data['login_time'] = time();
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
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/home');

    }

    /**
     * 自定义guard
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('home');
    }

    /**
     * 用户登录用户名
     * @return string
     */
    protected function username()
    {
        return 'username';
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
        $usernameLogin = $this->guard()->attempt(
            ['username' => $username, 'password' => $password], $request->filled('remember')
        );
        if ($usernameLogin) {
            return true;
        }
        //验证手机号登陆
        $mobileLogin = $this->guard()->attempt(
            ['mobile' => $username, 'password' => $password], $request->filled('remember')
        );
        if ($mobileLogin) {
            return true;
        }

        // 验证邮箱登录方式
        $emailLogin = $this->guard()->attempt(
            ['email' => $username, 'password' => $password], $request->filled('remember')
        );
        if ($emailLogin) {
            return true;
        }

        return false;
    }
}
