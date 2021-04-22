<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Http\Controllers\Controller;
use App\Repositories\Home\CityRepository;
use App\Models\Common\City;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{

    protected $callback;
    protected $currentUser;
    protected $auth;
    protected $userInfo;

    /**
     * 父类构造器
     * BaseController constructor.
     */
    public function __construct(){
        $this->callback = isset($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : '';

        $this->middleware(function ($request, $next) {
            if(Auth::guard('home')->check()){
                $userInfo = Auth::guard('home')->user();
                $this->userInfo = $userInfo;
                view()->share('userInfo',$userInfo);
            }

            return $next($request);
        });

        view()->share('callback',$this->callback);
    }
}
