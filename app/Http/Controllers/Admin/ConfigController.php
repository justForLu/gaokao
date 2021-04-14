<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ConfigRequest;
use App\Repositories\Admin\ConfigRepository;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ConfigController extends BaseController
{
    protected $config;
    protected $log;
    protected $rich_test;   //富文本唯一识别码数组
    protected $img; //图片唯一识别码数组
    protected $rob_index;   //抢单配置，只在抢单配置里展示，不在配置管理里展示了

    public function __construct(ConfigRepository $config,LogRepository $log)
    {
        parent::__construct();

        $this->config = $config;
        $this->log = $log;
        $this->rich_test = [
            'home_privacy_agreement',
            'home_platform_notice'
        ];
        $this->img = [
            'home_service_code',
        ];
        $this->rob_index = [
            'commodity_max_times',
            'commodity_overtime',
            'commodity_division_times',
            'commodity_freeze_bean_time',
            'commodity_one_spread_money',
            'commodity_two_spread_money',
            'team_thank_prize',
        ];

    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $manager_name = Auth::guard('admin')->user()->username;
        return view('admin.config.index',compact('manager_name'));
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
        $params['rob_index'] = $this->rob_index;

        $result = $this->config->getList($params);
        $list = $result['list'] ?? [];
        if(!empty($list)){
            foreach ($list as &$v){
                if(in_array($v['only_tag'],$this->rich_test)){
                    $v['value'] = "<span style='color: #b5b5b5;'>列表内不展示富文本</span>";
                }
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ConfigRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ConfigRequest $request)
    {
        //
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
        $data = $this->config->find($id);

        if(in_array($data->only_tag, $this->rich_test)){
            $data->is_rich = 1;
            $data->value = htmlspecialchars_decode($data->value);
        }else{
            $data->is_rich = 0;
        }
        if(in_array($data->only_tag,$this->img)){
            $data->is_img = 1;
        }else{
            $data->is_img = 0;
        }
        $manager_name = Auth::guard('admin')->user()->username;
        return view('admin.config.edit', compact('data','manager_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ConfigRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ConfigRequest $request, $id)
    {
        $params = $request->all();
        if(isset($params['only_tag']) && in_array($params['only_tag'], $this->rich_test)){
            $params['value'] = htmlspecialchars_decode($params['value']);
        }
        if(!isset($params['only_tag']) || empty($params['only_tag'])){
            return $this->ajaxError('未知错误');
        }
        $data = [
            'value' => $params['value'] ?? '',
            'update_time' => time()
        ];

        $result = $this->config->update($data, $id);

        $this->log->writeOperateLog($request,'编辑配置');   //日志记录

        if($result){
            //修改成功，把配置信息缓存起来
            Cache::put(Config::get('common.cache.website_config').$params['only_tag'], $params['value'], 1440);
        }

        return $this->ajaxAuto($result,'编辑配置');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
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
        $result = $this->config->update($data,$id);
        $this->log->writeOperateLog($request, '更新商品信息');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
