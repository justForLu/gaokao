<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ArticleEnum;
use App\Http\Requests\Admin\ArticleRequest;
use App\Repositories\Admin\ArticleRepository as Article;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{

    protected $article;
    protected $log;

    public function __construct(Article $article,LogRepository $log)
    {
        parent::__construct();

        $this->article = $article;
        $this->log = $log;


    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('admin.article.index');
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

        $result = $this->article->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v['type_name'] = ArticleEnum::getDesc($v['type']);
            }
        }

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
        return view('admin.article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ArticleRequest $request)
    {
        $params = $request->all();

        $data = [
            'title' => $params['title'] ?? '',
            'type' => $params['type'] ?? 0,
            'content' => $params['content'] ? htmlspecialchars_decode($params['content']) : '',
            'status' => $params['status'] ?? 0,
            'sort' => $params['sort'],
            'create_time' => time()
        ];

        $result = $this->article->createData($data);

        $this->log->writeOperateLog($request,'添加文章');   //日志记录

        return $this->ajaxAuto($result,'添加文章');
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
        $data = $this->article->find($id);

        $data->content = htmlspecialchars_decode($data->content);

        return view('admin.article.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ArticleRequest $request, $id)
    {
        $params = $request->all();

        $data = [
            'title' => $params['title'] ?? '',
            'type' => $params['type'] ?? 0,
            'content' => $params['content'] ? htmlspecialchars_decode($params['content']) : '',
            'status' => $params['status'] ?? 0,
            'sort' => $params['sort'],
            'update_time' => time()
        ];

        $result = $this->article->updateData($data, $id);

        $this->log->writeOperateLog($request,'编辑文章');   //日志记录

        return $this->ajaxAuto($result,'编辑文章');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = $this->article->delete($id);

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
        $result = $this->article->update($data,$id);
        $this->log->writeOperateLog($request, '更新文章');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}




