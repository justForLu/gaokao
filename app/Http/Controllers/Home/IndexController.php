<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Enums\CategoryEnum;
use App\Enums\PositionEnum;
use App\Repositories\Home\BannerRepository as Banner;
use App\Repositories\Home\CategoryRepository as Category;
use App\Repositories\Home\NewsRepository as News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController
{

    protected $banner;
    protected $category;
    protected $news;

    public function __construct(Banner $banner, Category $category, News $news)
    {
        parent::__construct();

        $this->banner = $banner;
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
        $where1['type'] = PositionEnum::INDEX;
        $banner = $this->banner->getList($where1);

        return view('home.index.index', compact('banner','user_id'));
    }

}
