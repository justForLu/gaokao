<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BasicEnum;
use App\Enums\PositionEnum;
use App\Http\Requests\Admin\BannerRequest;
use App\Repositories\Admin\BannerRepository as Banner;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;

class BannerController extends BaseController
{
    /**
     * @var Banner
     */
    protected $banner;
    protected $log;

    public function __construct(Banner $banner,LogRepository $log)
    {
        parent::__construct();

        $this->banner = $banner;
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        return view('admin.banner.index');
    }

    /**
     * 轮播图列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->banner->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v['position_name'] = PositionEnum::getDesc($v['position']);
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

        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BannerRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(BannerRequest $request)
    {
        $params = $request->filterAll();

        $data = [
            'title' => $params['title'] ?? '',
            'image' => $params['image'] ?? '',
            'url' => $params['url'] ?? '',
            'position' => $params['position'] ?? 0,
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 0,
            'create_time' => time()
        ];

        $result = $this->banner->create($data);
        $this->log->writeOperateLog($request,'添加轮播图');   //日志记录

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
        $params = $request->all();
        $params['id'] = $id;

        $data = $this->banner->find($id);

        return view('admin.banner.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BannerRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(BannerRequest $request, $id)
    {
        $params = $request->filterAll();

        $data = [
            'title' => $params['title'] ?? '',
            'image' => $params['image'] ?? '',
            'url' => $params['url'] ?? '',
            'position' => $params['position'] ?? 0,
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 0,
            'update_time' => time()
        ];

        $result = $this->banner->update($data,$id);

        $this->log->writeOperateLog($request, '更新轮播图');  //日志记录
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
        $result = $this->banner->delete($id);
        $this->log->writeOperateLog($request, '删除轮播图');  //日志记录

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
        $result = $this->banner->update($data,$id);
        $this->log->writeOperateLog($request, '更新轮播图');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }
}
