<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Enums\BatchEnum;
use App\Enums\ScienceEnum;
use App\Http\Requests\Admin\EnterLineRequest;
use App\Repositories\Admin\EnterLineRepository as EnterLine;
use App\Repositories\Admin\SchoolRepository as School;
use App\Repositories\Admin\CityRepository as City;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class EnterLineController extends BaseController
{
    protected $line;
    protected $school;
    protected $city;
    protected $log;

    public function __construct(EnterLine $line,School $school,City $city,LogRepository $log)
    {
        parent::__construct();

        $this->line = $line;
        $this->school = $school;
        $this->city = $city;
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
        //省份
        $where2 = ['parent'=>0];
        $province = $this->city->getCityList($where2);
        return view('admin.enter_line.index',compact('school','province'));
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
        $params['with'] = ['school','province'];
        $result = $this->line->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v['school_name'] = $v['school']['name'] ?? '-';
                $v['province_name'] = $v['province']['title'] ?? '-';
                $v['science_name'] = ScienceEnum::getDesc($v['science']);
                $v['batch_name'] = BatchEnum::getDesc($v['batch']);
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
        //省份
        $where2 = ['parent'=>0];
        $province = $this->city->getCityList($where2);
        return view('admin.enter_line.create',compact('school','province'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EnterLineRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(EnterLineRequest $request)
    {
        $params = $request->all();

        $data = [
            'school_id' => $params['school_id'] ?? 0,
            'province' => $params['province'] ?? 0,
            'year' => $params['year'] ?? 0,
            'science' => $params['science'] ?? 0,
            'batch' => $params['batch'] ?? 0,
            'min_score' => $params['min_score'] ?? 0,
            'min_rank' => $params['min_rank'] ?? 0,
            'control_line' => $params['control_line'] ?? 0,
            'create_time' => time()
        ];

        //判断数据是否已经存在
        $is_exist = $this->line->where('school_id',$data['school_id'])->where('province',$data['province'])
            ->where('year',$data['year'])->where('batch',$data['batch'])->count();
        if($is_exist > 0){
            return $this->ajaxError('已存在的分数线数据');
        }

        $result = $this->line->create($data);

        $this->log->writeOperateLog($request, '添加高校各省录取分数线');  //日志记录
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
        $data = $this->line->find($id);

        //高校
        $where[] = ['status','=',BasicEnum::ACTIVE];
        $field = ['id','name'];
        $school = $this->school->getAllList($where,$field);
        //省份
        $where2 = ['parent'=>0];
        $province = $this->city->getCityList($where2);
        return view('admin.enter_line.edit',compact('data','school','province'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EnterLineRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(EnterLineRequest $request, $id)
    {
        $params = $request->all();

        $data = [
            'school_id' => $params['school_id'] ?? 0,
            'province' => $params['province'] ?? 0,
            'year' => $params['year'] ?? 0,
            'science' => $params['science'] ?? 0,
            'batch' => $params['batch'] ?? 0,
            'min_score' => $params['min_score'] ?? 0,
            'min_rank' => $params['min_rank'] ?? 0,
            'control_line' => $params['control_line'] ?? 0,
            'update_time' => time()
        ];
        //判断数据是否已经存在
        $is_exist = $this->line->where('id','<>',$id)->where('school_id',$data['school_id'])
            ->where('province',$data['province'])->where('year',$data['year'])->where('batch',$data['batch'])->count();
        if($is_exist > 0){
            return $this->ajaxError('已存在的分数线数据');
        }

        $result = $this->line->update($data,$id);

        $this->log->writeOperateLog($request, '更新高校各省录取分数线');  //日志记录
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
        $result = $this->line->delete($id);
        $this->log->writeOperateLog($request, '删除高校各省录取分数线');  //日志记录

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
        $result = $this->line->update($data,$id);
        $this->log->writeOperateLog($request, '更新高校各省录取分数线');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
