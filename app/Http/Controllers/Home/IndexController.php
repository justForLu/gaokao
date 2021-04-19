<?php
namespace App\Http\Controllers\Home;


use App\Enums\BannerEnum;
use App\Enums\BasicEnum;
use App\Enums\CategoryEnum;
use App\Repositories\Home\BannerRepository as Banner;
use App\Repositories\Home\RecruitRepository as Recruit;
use App\Repositories\Home\ShopRepository as Shop;
use App\Repositories\Home\CategoryRepository as Category;
use App\Repositories\Home\NewsRepository as News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController
{

    protected $banner;
    protected $recruit;
    protected $shop;
    protected $category;
    protected $news;

    public function __construct(Banner $banner, Recruit $recruit, Shop $shop, Category $category, News $news)
    {
        parent::__construct();

        $this->banner = $banner;
        $this->recruit = $recruit;
        $this->shop = $shop;
        $this->category = $category;
        $this->news = $news;

        view()->share('menu','Index');
    }

    /**
     * 网站首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index()
    {
        $user_id = isset($this->userInfo->id) ? $this->userInfo->id : 0;
        //banner图
        $where1['type'] = BannerEnum::INDEX;
        $banner_arr = $this->banner->getList($where1);

        //今日热招
        $where2['is_recommend']['EQ'] = BasicEnum::ACTIVE;
        $with2 = ['factory'];
        $recruit_arr = $this->recruit->getList('*',$where2,4,$with2);

        //门店服务
        $where3['is_recommend']['EQ'] = BasicEnum::ACTIVE;
        $shop_arr = $this->shop->getList('*',$where3,5);

        return view('home.index.index', compact('banner_arr','recruit_arr','shop_arr','user_id'));
    }

}
