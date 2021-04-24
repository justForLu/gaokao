<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Enums\BoolEnum;
use App\Enums\CategoryEnum;
use App\Repositories\Home\Criteria\ArticleCriteria;
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
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
	public function index(Request $request)
    {
        $params = $request->all();

        $this->article->pushCriteria(new ArticleCriteria($params));

        $list = $this->article->paginate(Config::get('home.page_size',10));
        //文章分类
        $category = \App\Models\Common\Category::where('status',BasicEnum::ACTIVE)
            ->where('type',CategoryEnum::ARTICLE)
            ->orderBy('sort','DESC')
            ->get();
        //热门文章
        $where1['is_recommend']['EQ'] = BoolEnum::YES;
        $article = $this->article->getList('*',$where1,6);

        return view('home.article.index', compact('list','article','category'));
    }

    /**
     * 新闻详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        $params = $request->all();
        $id = $params['id'] ?? 0;
        $data = $this->article->find($id);
        $data->content = htmlspecialchars_decode($data->content);

        //发布作者
        $user = $this->manager->find($data->author);
        $data->author = isset($user->username) ? $user->username : '';

        //热门文章
        $where1['is_recommend']['EQ'] = BoolEnum::YES;
        $article = $this->article->getList('*',$where1,6);

        //增加一次阅读量
        $this->article->increment('read',1, ['update_time' => time()]);

        return view('home.article.detail',compact('data','article'));
    }

}
