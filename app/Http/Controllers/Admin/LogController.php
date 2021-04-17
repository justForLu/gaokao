<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\Admin\LogRepository;
use App\Repositories\Admin\Criteria\LogCriteria;

class LogController extends BaseController
{
    /**
     * @var LogRepository
     */
    protected $log;

    public function __construct(LogRepository $log)
    {
        parent::__construct();

        $this->log = $log;
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('admin.log.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $this->log->pushCriteria(new LogCriteria($params));

        $params['with'] = ['manager'];
        $result = $this->log->getList($params);
        $list = $result['list'] ?? [];
        if($list){
            foreach ($list as &$v){
                $v->username = $v->manager->username ?? '';
                $v->create_time = date('Y-m-d H:i:s', $v->create_time);
            }
        }

        return $this->ajaxData($list,$result['count'],'OK');
    }

}
