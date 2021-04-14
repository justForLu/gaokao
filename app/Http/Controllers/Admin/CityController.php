<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\CityRepository as City;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    /**
     * @var City
     */
    protected $city;

    public function __construct(City $city)
    {
        parent::__construct();

        $this->city = $city;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.city.index');
    }

    /**
     * 获取列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->city->getList($params);

        return $this->ajaxData($result['list'],$result['count'],'OK');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.city.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    /**
     * 获取区域id上级区域
     *
     * @param Request|Request $request
     * @return \Illuminate\Http\Response
     */
    public function get_city_path(Request $request)
    {
        $id = $request->input('id');
        $list = $this->city->get_city_path($id);

        return response()->json($list);
    }

    /**
     * 获取区域id下级区域
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function get_city_list(Request $request){
        $id = $request->input('id');
        if(!isset($id) || empty($id)){
            $params['parent'] = 0;
            $params['grade'] = 1;
        }else{
            $params['parent'] = $id;
        }

        $list = $this->city->getCityList($params);
        return response()->json($list);
    }

    /**
     * 根据城市id获取与该城市关联的省市区等信息
     * @param $id
     * @param $type
     * @return array
     */
    static function getCityArrString($id,$type = 'arr'){
        $cityLabel = array('province','city','area');
        $cityArr = array();
        $cityId = array();
        $cityName='';

        do{
            $city = \App\Models\Common\City::select('*')->where('id',$id)->first($id);
            if($city){
                $cityArr[$cityLabel[$city->grade-1]] = $city->title;
                $cityId[$cityLabel[$city->grade-1]] = $city->id;
                $cityName=$city->title.$cityName;
                $id = $city->parent;
            }
        }while(isset($city->parent) && $city->parent != 0);

        if($type == 'string'){
            return $cityName;//返回字符串
        }elseif($type == 'id'){
            return $cityId;//返回ID
        }else{
            return $cityArr;//返回数组
        }
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
        $result = $this->city->update($data,$id);
        return $this->ajaxAuto($result,'修改');
    }

}
