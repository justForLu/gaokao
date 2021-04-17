<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\FeedbackRepository as Feedback;
use App\Repositories\Admin\LogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends BaseController
{
    protected $feedback;
    protected $log;

    public function __construct(Feedback $feedback,LogRepository $log)
    {
        parent::__construct();

        $this->feedback = $feedback;
        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('admin.feedback.index');
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
        $params['with'] = ['manager'];
        $result = $this->feedback->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v['manager'] = $v['manager']['username'] ?? '-';
                $v['deal_time'] = $v['deal_time'] > 0 ? date('Y-m-d H:i:s',$v['deal_time']) : '-';
                $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            }
        }

        return $this->ajaxData($list,$result['count'],'OK');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
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
        $data = $this->feedback->find($id);

        return view('admin.feedback.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, $id)
    {
        $params = $request->all();

        $data = [
            'remark' => $params['remark'] ?? '',
            'status' => $params['status'] ?? 0,
            'admin_id' => Auth::guard('admin')->user()->id,
            'deal_time' => time(),
            'update_time' => time()
        ];

        $result = $this->feedback->update($data,$id);

        $this->log->writeOperateLog($request, '处理意见返回');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param Request $request
     */
    public function destroy($id, Request $request)
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
        if(empty($id) || empty($field)){
            return $this->ajaxError('未知错误，请联系管理员');
        }
        $data = [
            $field => $value,
            'admin_id' => Auth::guard('admin')->user()->id,
            'deal_time' => time(),
            'update_time' => time()
        ];
        $result = $this->feedback->update($data,$id);
        $this->log->writeOperateLog($request, '更新反馈信息');  //日志记录
        return $this->ajaxAuto($result,'修改');
    }

}
