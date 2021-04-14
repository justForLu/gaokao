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
     * 获取分类列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $params = $request->all();
        $field = ['id','name','image'];
        $list = $this->category->getList($params,$field);
        if($list){
            foreach ($list as &$v){
                $v['image'] = get_http_type().$_SERVER['HTTP_HOST'].$v['image'];
            }
        }

        return $this->returnSuccess($list);
    }


}




