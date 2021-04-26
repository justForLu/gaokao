<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Http\Requests\Admin\SchoolRequest;
use App\Models\Common\City;
use App\Models\Common\Tag;
use App\Repositories\Admin\SchoolRepository as School;
use App\Repositories\Admin\LogRepository;
use App\Repositories\Admin\CityRepository;
use Illuminate\Http\Request;

class SchoolController extends BaseController
{
    protected $school;
    protected $city;
    protected $log;

    public function __construct(School $school,CityRepository $city,LogRepository $log)
    {
        parent::__construct();

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
        $where = ['parent'=>0];
        $province = $this->city->getCityList($where);
        return view('admin.school.index',compact('province'));
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

        $result = $this->school->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            //省市信息
            $province = array_unique(array_column($list,'province'));
            $city = array_unique(array_column($list,'city'));
            $area = array_unique(array_column($list, 'area'));

            $region_id = array_unique(array_merge($province,$city,$area));
            $region_id = array_diff($region_id,[0]);
            $city_list = [];
            if($region_id && !empty($region_id[0])){
                $city_list = City::whereIn('id',$region_id)->pluck('title','id');
            }
            foreach ($list as &$v){
                //省市县信息
                $province_name = $city_list[$v['province']] ?? '-';
                $city_name = $city_list[$v['city']] ?? '-';
                $area_name = $city_list[$v['area']] ?? '-';
                $v['province_city'] = $province_name.'-'.$city_name.'-'.$area_name;
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
        //省份
        $where['parent'] = 0;
        $where['grade'] = 1;
        $province = $this->city->getCityList($where);
        //标签
        $tag = Tag::where('status',BasicEnum::ACTIVE)->orderBy('sort','DESC')->get();

        return view('admin.school.create',compact('province','tag'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SchoolRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(SchoolRequest $request)
    {
        $params = $request->all();

        $data = [
            'name' => $params['name'] ?? '',
            'logo' => $params['logo'] ?? '',
            'province' => $params['province'] ?? 0,
            'city' => $params['city'] ?? 0,
            'area' => $params['area'] ?? 0,
            'address' => $params['address'] ?? '',
            'website' => $params['website'] ?? '',
            'phone' => $params['phone'] ?? '',
            'email' => $params['email'] ?? '',
            'measure' => $params['measure'] ?? 0,
            'belong' => $params['belong'] ?? '',
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 0,
            'content' => $params['content'] ? htmlspecialchars_decode($params['content']) : '',
            'create_time' => time()
        ];
        //判断高校是否已经存在
        $is_exist = $this->school->where('name',$data['name'])->count();
        if($is_exist > 0){
            return $this->ajaxError('已存在的高校');
        }
        //高校标签
        $tag = $params['tag'] ?? [];
        $tag_str = ','.implode(',',$tag).',';
        $data['tag'] = $tag_str;

        $result = $this->school->create($data);

        $this->log->writeOperateLog($request, '添加高校');  //日志记录
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
        $data = $this->school->find($id);
        //省市县
        $where1['parent'] = 0;
        $where1['grade'] = 1;
        $province = $this->city->getCityList($where1);
        $where2['parent'] = $data->province;
        $city = $this->city->getCityList($where2);
        $where3['parent'] = $data->city;
        $area = $this->city->getCityList($where3);
        //标签
        $tag = Tag::where('status',BasicEnum::ACTIVE)->orderBy('sort','DESC')->get();
        $tag_arr = explode(',',$data->tag);
        if(!empty($tag) && !empty($tag_arr)){
            foreach ($tag as &$v){
                if(in_array($v['id'],$tag_arr)){
                    $v['checked'] = 1;
                }
            }
        }

        return view('admin.school.edit',compact('data','province','city','area','tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SchoolRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(SchoolRequest $request, $id)
    {
        $params = $request->all();

        $data = [
            'name' => $params['name'] ?? '',
            'logo' => $params['logo'] ?? '',
            'province' => $params['province'] ?? 0,
            'city' => $params['city'] ?? 0,
            'area' => $params['area'] ?? 0,
            'address' => $params['address'] ?? '',
            'website' => $params['website'] ?? '',
            'phone' => $params['phone'] ?? '',
            'email' => $params['email'] ?? '',
            'measure' => $params['measure'] ?? 0,
            'belong' => $params['belong'] ?? '',
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 0,
            'content' => $params['content'] ? htmlspecialchars_decode($params['content']) : '',
            'update_time' => time()
        ];
        //判断高校是否已经存在
        $is_exist = $this->school->where('id','<>',$id)->where('name',$data['name'])->count();
        if($is_exist > 0){
            return $this->ajaxError('已存在的高校');
        }
        //高校标签
        $tag = $params['tag'] ?? [];
        $tag_str = ','.implode(',',$tag).',';
        $data['tag'] = $tag_str;

        $result = $this->school->update($data,$id);

        $this->log->writeOperateLog($request, '更新高校');  //日志记录
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
        $result = $this->school->delete($id);
        $this->log->writeOperateLog($request, '删除高校');  //日志记录

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
        $result = $this->school->update($data,$id);
        $this->log->writeOperateLog($request, '更新高校');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
