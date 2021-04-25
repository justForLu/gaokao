<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Enums\BoolEnum;
use App\Enums\CategoryEnum;
use App\Repositories\Home\ArticleRepository as Article;
use App\Repositories\Home\CategoryRepository as Category;
use App\Repositories\Admin\ManagerRepository as Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ArticleController extends BaseController
{

    protected $article;
    protected $category;
    protected $manager;

    public function __construct(Article $article, Category $category, Manager $manager)
    {
        parent::__construct();

        $this->article = $article;
        $this->category = $category;
        $this->manager = $manager;

        view()->share('menu','Article');
    }

    /**
     * 文章首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
	public function index(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Config::get('home.page_size',10);
        $list = $this->article->getList($params);
        $count = $list['count'] ?? 0;
        $list = $list['list'] ?? [];

        //文章分类
        $where1 = [
            'status' => BasicEnum::ACTIVE,
            'type' => CategoryEnum::ARTICLE
        ];
        $category = $this->category->getList($where1);
        //热门文章
        $where2 = [
            'is_recommend' => BoolEnum::YES,
            'limit' => 6,
        ];
        $article = $this->article->getList($where2);
        $article = $article['list'] ?? [];

        return view('home.article.index', compact('params','list','count','category','article'));
    }

    /**
     * 新闻详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($id)
    {
        $data = $this->article->find($id);

        $data->content = htmlspecialchars_decode($data->content);
$data->author = $this->manager->find($data->author);
        //热门文章
        $where1 = [
            'is_recommend' => BoolEnum::YES,
            'limit' => 6,
        ];
        $article = $this->article->getList($where1);
        $article = $article['list'] ?? [];

        //增加一次阅读量
        $this->article->where('id',$id)->increment('read',1, ['update_time' => time()]);

        return view('home.article.detail',compact('data','article'));
    }

}
