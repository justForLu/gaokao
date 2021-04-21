<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Enums\CategoryEnum;
use App\Enums\MajorEnum;
use App\Enums\MajorTypeEnum;
use App\Http\Requests\Admin\MajorRequest;
use App\Repositories\Admin\MajorRepository as Major;
use App\Repositories\Admin\CategoryRepository as Category;
use App\Repositories\Admin\SchoolRepository as School;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class MajorController extends BaseController
{
    protected $major;
    protected $school;
    protected $category;
    protected $log;

    public function __construct(Major $major,School $school,Category $category,LogRepository $log)
    {
        parent::__construct();

        $this->major = $major;
        $this->school = $school;
        $this->category = $category;
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        //高校
        $where[] = ['status','=',BasicEnum::ACTIVE];
        $field = ['id','name'];
        $school = $this->school->getAllList($where,$field);
        //分类
        $where2[] = ['type','=',CategoryEnum::MAJOR];
        $field2 = ['id','name'];
        $category = $this->category->getAllList($where2,$field2);
        return view('admin.major.index',compact('school','category'));
    }

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();
        $params['with'] = ['school','category'];
        $result = $this->major->getList($params);
        $list = $result['list'] ?? [];
        if(!empty($list)){
            foreach ($list as &$v){
                $v['school_name'] = $v['school']['name'] ?? '-';
                $v['category_name'] = $v['category']['name'] ?? '-';
                $v['type_name'] = $v['type'] > 0 ? MajorTypeEnum::getDesc($v['type']) : '-';
                $v['grade_name'] = $v['grade'] > 0 ? MajorEnum::getDesc($v['grade']) : '-';
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
        //高校
        $where[] = ['status','=',BasicEnum::ACTIVE];
        $field = ['id','name'];
        $school = $this->school->getAllList($where,$field);
        //分类
        $where2[] = ['type','=',CategoryEnum::MAJOR];
        $field2 = ['id','name'];
        $category = $this->category->getAllList($where2,$field2);
        return view('admin.major.create',compact('school','category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MajorRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(MajorRequest $request)
    {
        $params = $request->all();

        $data = [
            'school_id' => $params['school_id'] ?? 0,
            'category_id' => $params['category_id'] ?? 0,
            'name' => $params['name'] ?? '',
            'type' => $params['type'] ?? 0,
            'grade' => $params['grade'] ?? 0,
            'edu_system' => $params['edu_system'] ?? 0,
            'sort' => $params['sort'] ?? 0,
            'content' => $params['content'] ? htmlspecialchars_decode($params['content']) : '',
            'create_time' => time()
        ];

        $result = $this->major->create($data);

        $this->log->writeOperateLog($request, '添加高校专业');  //日志记录
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
        //高校
        $where[] = ['status','=',BasicEnum::ACTIVE];
        $field = ['id','name'];
        $school = $this->school->getAllList($where,$field);
        //分类
        $where2[] = ['type','=',CategoryEnum::MAJOR];
        $field2 = ['id','name'];
        $category = $this->major->getAllList($where2,$field2);
        return view('admin.major.edit',compact('data','school','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MajorRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(MajorRequest $request, $id)
    {
        $params = $request->all();

        $data = [
            'school_id' => $params['school_id'] ?? 0,
            'category_id' => $params['category_id'] ?? 0,
            'name' => $params['name'] ?? '',
            'type' => $params['type'] ?? 0,
            'grade' => $params['grade'] ?? 0,
            'edu_system' => $params['edu_system'] ?? 0,
            'sort' => $params['sort'] ?? 0,
            'content' => $params['content'] ? htmlspecialchars_decode($params['content']) : '',
            'update_time' => time()
        ];

        $result = $this->major->update($data,$id);

        $this->log->writeOperateLog($request, '更新高校专业');  //日志记录
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
        $result = $this->major->delete($id);
        $this->log->writeOperateLog($request, '删除高校专业');  //日志记录

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
        $result = $this->major->update($data,$id);
        $this->log->writeOperateLog($request, '更新高校专业');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

    /**
     * 获取专业列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMajorList(Request $request)
    {
        $params = $request->all();

        $school_id = $params['school_id'] ?? 0;
        $where[] = ['school_id','=',$school_id];
        $field = ['id','name'];

        $list = $this->major->getAllList($where,$field);

        return $this->ajaxSuccess($list,'OK');
    }
}
