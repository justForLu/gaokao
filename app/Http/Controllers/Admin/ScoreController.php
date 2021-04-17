<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Http\Requests\Admin\ScoreRequest;
use App\Models\Common\City;
use App\Repositories\Admin\ScoreRepository as Score;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class ScoreController extends BaseController
{
    protected $score;
    protected $log;

    public function __construct(Score $score,LogRepository $log)
    {
        parent::__construct();

        $this->score = $score;
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $province = City::where('parent',0)->where('status',BasicEnum::ACTIVE)->orderBy('sort','DESC')->get();
        return view('admin.score.index',compact('province'));
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

        $result = $this->score->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            $province = array_diff(array_unique(array_column($list,'province')),[0]);
            $province_list = City::where('id',$province)->pluck('title','id');
            foreach ($list as &$v){
                $v['province_name'] = $province_list[$v['province']] ?? '-';
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
        $province = City::where('parent',0)->where('status',BasicEnum::ACTIVE)->orderBy('sort','DESC')->get();
        return view('admin.score.create',compact('province'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ScoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ScoreRequest $request)
    {
        $params = $request->all();

        $data = [
            'province' => $params['province'] ?? 0,
            'year' => $params['year'] ?? '',
            'yiben_li' => $params['yiben_li'] ?? 0,
            'erben_li' => $params['erben_li'] ?? 0,
            'sanben_li' => $params['sanben_li'] ?? 0,
            'dazhuan_li' => $params['dazhuan_li'] ?? 0,
            'yiben_wen' => $params['yiben_wen'] ?? 0,
            'erben_wen' => $params['erben_wen'] ?? 0,
            'sanben_wen' => $params['sanben_wen'] ?? 0,
            'dazhuan_wen' => $params['dazhuan_wen'] ?? 0,
            'create_time' => time()
        ];

        //同一省份同一年份只能有一条数据
        $is_exist = $this->score->where('province',$data['province'])->where('year',$data['year'])->count();
        if($is_exist > 0){
            return $this->ajaxError('已存在的省份&年份分数线');
        }

        $result = $this->score->create($data);

        $this->log->writeOperateLog($request, '添加分数线');  //日志记录
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
        $data = $this->score->find($id);
        $province = City::where('parent',0)->where('status',BasicEnum::ACTIVE)->orderBy('sort','DESC')->get();
        return view('admin.score.edit',compact('data','province'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ScoreRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ScoreRequest $request, $id)
    {
        $params = $request->filterAll();

        $data = [
            'province' => $params['province'] ?? 0,
            'year' => $params['year'] ?? '',
            'yiben_li' => $params['yiben_li'] ?? 0,
            'erben_li' => $params['erben_li'] ?? 0,
            'sanben_li' => $params['sanben_li'] ?? 0,
            'dazhuan_li' => $params['dazhuan_li'] ?? 0,
            'yiben_wen' => $params['yiben_wen'] ?? 0,
            'erben_wen' => $params['erben_wen'] ?? 0,
            'sanben_wen' => $params['sanben_wen'] ?? 0,
            'dazhuan_wen' => $params['dazhuan_wen'] ?? 0,
            'update_time' => time()
        ];

        //同一省份同一年份只能有一条数据
        $is_exist = $this->score->where('id','<>',$id)->where('province',$data['province'])->where('year',$data['year'])->count();
        if($is_exist > 0){
            return $this->ajaxError('已存在的省份&年份分数线');
        }

        $result = $this->score->update($data,$id);

        $this->log->writeOperateLog($request, '更新分数线');  //日志记录
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

        $result = $this->score->delete($id);
        $this->log->writeOperateLog($request, '删除分数线');  //日志记录

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
        $result = $this->score->update($data,$id);
        $this->log->writeOperateLog($request, '更新分数线');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
