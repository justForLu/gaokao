<?php

namespace App\Http\Controllers\Api;

use App\Enums\ArticleEnum;
use App\Repositories\Api\ScoreRepository as Score;
use Illuminate\Http\Request;

class ScoreController extends BaseController
{
    protected $score;

    public function __construct(Score $score,Request $request)
    {
        parent::__construct($request);

        $this->score = $score;

    }

    /**
     * 获取公告列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function getScore(Request $request)
    {
        $params = $request->all();

        $result = $this->score->getList($params);

        return $this->returnSuccess($result);
    }

}




