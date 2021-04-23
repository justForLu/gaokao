<?php
namespace App\Http\Controllers\Home;

use App\Enums\PositionEnum;
use App\Repositories\Home\BannerRepository as Banner;
use App\Repositories\Home\CategoryRepository as Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController
{

    protected $banner;
    protected $category;
    protected $news;

    public function __construct(Banner $banner, Category $category)
    {
        parent::__construct();

        $this->banner = $banner;
        $this->category = $category;

        view()->share('menu','Index');
    }

    /**
     * 网站首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index()
    {
        //banner图
        $where1['position'] = PositionEnum::INDEX;
        $banner = $this->banner->getList($where1);

        return view('home.index.index', compact('banner'));
    }

}
