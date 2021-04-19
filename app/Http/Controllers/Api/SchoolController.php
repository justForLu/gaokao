<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\SchoolRepository as School;
use Illuminate\Http\Request;

class SchoolController extends BaseController
{
    protected $school;

    public function __construct(School $school,Request $request)
    {
        parent::__construct($request);

        $this->school = $school;

    }

    /**
     * 获取高校列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->school->getList($params);

        return $this->returnSuccess($result);
    }

    /**
     * 获取高校详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(Request $request)
    {
        $params = $request->all();
        $id = $params['id'] ?? 0;

        $data = $this->school->getDetail($id);

        return $this->returnSuccess($data);
    }

}




