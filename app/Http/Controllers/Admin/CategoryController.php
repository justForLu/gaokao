<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryEnum;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Common\Article;
use App\Models\Common\Major;
use App\Repositories\Admin\CategoryRepository as Category;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $category;
    protected $log;

    public function __construct(Category $category,LogRepository $log)
    {
        parent::__construct();

        $this->category = $category;
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('admin.category.index');
    }

    /**
     * 获取列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->category->getList($params);
        $list = $result['list'] ?? [];

        return $this->ajaxData($list,$result['count'],'OK');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CategoryRequest $request)
    {
        $params = $request->all();

        $data = [
            'name' => $params['name'] ?? '',
            'type' => $params['type'] ?? 0,
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 0,
            'create_time' => time()
        ];

        $result = $this->category->create($data);

        $this->log->writeOperateLog($request, '添加分类');  //日志记录
        return $this->ajaxAuto($result,'添加');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $data = $this->category->find($id);

        return view('admin.category.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CategoryRequest $request, $id)
    {
        $params = $request->all();

        $data = [
            'name' => $params['name'] ?? '',
            'type' => $params['type'] ?? 0,
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 0,
            'update_time' => time()
        ];

        $result = $this->category->update($data,$id);

        $this->log->writeOperateLog($request, '更新分类');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Request $request)
    {
        //删除之前检查分类下是否存在使用中的情况
        $category = $this->category->find($id);
        if($category->type == CategoryEnum::ARTICLE){
            $is_exist = Article::where('category_id',$id)->count();
            if($is_exist > 0){
                return $this->ajaxError('文章中存在该分类，不能被删除');
            }
        }elseif ($category->type == CategoryEnum::MAJOR){
            $is_exist = Major::where('category_id',$id)->count();
            if($is_exist > 0){
                return $this->ajaxError('高校专业中存在该分类，不能被删除');
            }
        }

        $result = $this->category->delete($id);
        $this->log->writeOperateLog($request, '删除分类');  //日志记录

        return $this->ajaxAuto($result,'删除');
    }

    /**
     * 表格单元格修改数据专用
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function changeValue(Request $request)
    {
        $params = $request->all();
        $id = $params['id'] ?? 0;
        $field = $params['field'] ?? '';
        $value = $params['value'] ?? '';
        if(empty($id) || empty($field) || empty($value)){
            return $this->ajaxError('未知错误，请联系管理员');
        }
        $data = [
            $field => $value,
            'update_time' => time()
        ];
        $result = $this->category->update($data,$id);
        $this->log->writeOperateLog($request, '更新分类');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
