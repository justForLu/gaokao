<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Manager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class BaseController extends Controller
{

    protected $currentUser;
    protected $auth;
    protected $userInfo;

    /**
     * 父类构造器
     * BaseController constructor.
     */
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if(Auth::guard('admin')->check()){
                $userMenus = $this->getUserMenus();
                $this->currentUser = $this->getCurrentUser();

                view()->share('userMenus',$userMenus);
            }

            return $next($request);
        });
    }

    /**
     * 获取用户的菜单树
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getUserMenus(){
        $uid = Auth::guard('admin')->user()->id;
        $menuTree = json_decode(Cache::store('file')->get('menu_user_' . $uid));

        $uri = Request::path();

        // 获取激活的菜单
        foreach($menuTree as $key => $val){
            if(isset($val->children)){
                foreach($val->children as $itemKey => $itemVal){
                    if($this->startsWith($uri . '/','admin' . $itemVal->url . '/')){  // admin/order_inv  admin/order
                        $menuTree[$key]->children[$itemKey]->active = true;
                        $menuTree[$key]->active = true;
                        break;
                    }
                }
            }else{
                if($this->startsWith($uri . '/','admin' . $val->url . '/')){
                    $menuTree[$key]->active = true;
                    break;
                }
            }
        }

        return $menuTree;
    }

    /**
     * 获取当前用户信息
     * @return mixed
     */
    public function getCurrentUser(){
        $uid = Auth::guard('admin')->user()->id;

        $currentUser = Manager::where('id',$uid)->with('roles')->first();

        return $currentUser;
    }

    /**
     * start with
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * end with
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
}
