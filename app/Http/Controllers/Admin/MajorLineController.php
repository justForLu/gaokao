<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Enums\BatchEnum;
use App\Enums\ScienceEnum;
use App\Http\Requests\Admin\MajorLineRequest;
use App\Models\Common\Major;
use App\Repositories\Admin\MajorLineRepository as Line;
use App\Repositories\Admin\SchoolRepository as School;
use App\Repositories\Admin\CityRepository as City;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class MajorLineController extends BaseController
{
    protected $line;
    protected $school;
    protected $city;
    protected $log;

    public function __construct(Line $line,School $school,City $city,LogRepository $log)
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
        return view('admin.major_line.index',compact('school','province'));
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
            //专业
            $major_id = array_diff(array_unique(array_column($list,'major_id')),[0]);
            $major_list = [];
            if($major_id){
                $major_list = Major::whereIn('id',$major_id)->pluck('name','id');
            }
            foreach ($list as &$v){
                $v['school_name'] = $v['school']['name'] ?? '-';
                $v['major_name'] = $major_list[$v['major_id']] ?? '-';
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
        return view('admin.major_line.create',compact('school','province'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MajorLineRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(MajorLineRequest $request)
    {
        $params = $request->all();

        $data = [
            'school_id' => $params['school_id'] ?? 0,
            'major_id' => $params['major_id'] ?? 0,
            'province' => $params['province'] ?? 0,
            'year' => $params['year'] ?? 0,
            'science' => $params['science'] ?? 0,
            'batch' => $params['batch'] ?? 0,
            'max_score' => $params['max_score'] ?? 0,
            'avg_score' => $params['avg_score'] ?? 0,
            'min_score' => $params['min_score'] ?? 0,
            'min_rank' => $params['min_rank'] ?? 0,
            'recruit_num' => $params['recruit_num'] ?? 0,
            'sign_num' => $params['sign_num'] ?? 0,
            'enter_num' => $params['enter_num'] ?? 0,
            'create_time' => time()
        ];

        $result = $this->line->create($data);

        $this->log->writeOperateLog($request, '添加高校各专业在各省的录取分数线');  //日志记录
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
        //专业
        $major = Major::select('id','name')->where('school_id',$data->school_id)->get();
        //省份
        $where2 = ['parent'=>0];
        $province = $this->city->getCityList($where2);

        return view('admin.major_line.edit',compact('data','school','major','province'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MajorLineRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(MajorLineRequest $request, $id)
    {
        $params = $request->all();

        $data = [
            'school_id' => $params['school_id'] ?? 0,
            'major_id' => $params['major_id'] ?? 0,
            'province' => $params['province'] ?? 0,
            'year' => $params['year'] ?? 0,
            'science' => $params['science'] ?? 0,
            'batch' => $params['batch'] ?? 0,
            'max_score' => $params['max_score'] ?? 0,
            'avg_score' => $params['avg_score'] ?? 0,
            'min_score' => $params['min_score'] ?? 0,
            'min_rank' => $params['min_rank'] ?? 0,
            'recruit_num' => $params['recruit_num'] ?? 0,
            'sign_num' => $params['sign_num'] ?? 0,
            'enter_num' => $params['enter_num'] ?? 0,
            'update_time' => time()
        ];

        $result = $this->line->update($data,$id);

        $this->log->writeOperateLog($request, '更新高校各专业在各省的录取分数线');  //日志记录
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
        $this->log->writeOperateLog($request, '删除高校各专业在各省的录取分数线');  //日志记录

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
        $this->log->writeOperateLog($request, '更新高校各专业在各省的录取分数线');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
