<?php

namespace App\Http\Controllers\Api;

use App\Enums\ArticleEnum;
use App\Repositories\Api\ArticleRepository as Article;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{

    protected $article;

    public function __construct(Article $article,Request $request)
    {
        parent::__construct($request);

        $this->article = $article;

    }

    /**
     * 获取公告列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $field = ['id','category_id','title','introduce'];
        $result = $this->article->getList($params, $field);

        return $this->returnSuccess($result);
    }

    /**
     * 获取文章详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(Request $request)
    {
        $params = $request->all();
        $id = $params['id'] ?? 0;
        $article = $this->article->select('id','title','content','create_time')->find($id);

        $article->content = htmlspecialchars_decode($article->content);

        return $this->returnSuccess($article);
    }

}




