<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Common\School;
use App\Repositories\Admin\TagRepository as Tag;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    protected $tag;
    protected $log;

    public function __construct(Tag $tag,LogRepository $log)
    {
        parent::__construct();

        $this->tag = $tag;
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('admin.tag.index');
    }

    /**
     * 分类列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->tag->getList($params);
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
        return view('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(TagRequest $request)
    {
        $params = $request->all();

        $data = [
            'name' => $params['name'] ?? '',
            'shorter' => $params['shorter'] ?? '',
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 0,
            'create_time' => time()
        ];

        $result = $this->tag->create($data);

        $this->log->writeOperateLog($request, '添加高校标签');  //日志记录
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
        $data = $this->tag->find($id);

        return view('admin.tag.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TagRequest $request, $id)
    {
        $params = $request->all();

        $data = [
            'name' => $params['name'] ?? '',
            'shorter' => $params['shorter'] ?? '',
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 0,
            'update_time' => time()
        ];

        $result = $this->tag->update($data,$id);

        $this->log->writeOperateLog($request, '更新高校标签');  //日志记录
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
        //检查高校中是否存在该标签，存在则不允许删除
        $is_exist = School::select('id')->where('tag','LIKE','%,'.$id.',%')->count();
        if($is_exist > 0){
            return $this->ajaxError('高校中存在该标签，不能删除');
        }

        $result = $this->tag->delete($id);
        $this->log->writeOperateLog($request, '删除高校标签');  //日志记录

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
        $result = $this->tag->update($data,$id);
        $this->log->writeOperateLog($request, '更新高校标签');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
