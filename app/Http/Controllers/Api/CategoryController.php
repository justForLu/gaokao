<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\CategoryRepository as Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{

    protected $category;

    public function __construct(Category $category,Request $request)
    {
        parent::__construct($request);

        $this->category = $category;

    }

    /**
     * 获取文章分类列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtList(Request $request)
    {
        $params = $request->all();
        $field = ['id','name'];
        $list = $this->category->getList($params,$field);

        return $this->returnSuccess($list);
    }


}




