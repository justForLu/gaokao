<?php

namespace App\Http\Controllers\Api;

use App\Enums\BasicEnum;
use App\Http\Requests\Api\FeedbackRequest;
use App\Repositories\Api\FeedbackRepository as Feedback;
use Illuminate\Http\Request;

class FeedbackController extends BaseController
{
    protected $feedback;

    public function __construct(Feedback $feedback,Request $request)
    {
        parent::__construct($request);

        $this->feedback = $feedback;

    }

    /**
     * 提交反馈
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $params = $request->all();

        return $this->returnSuccess(null,'提交成功');
    }
}
