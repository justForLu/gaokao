<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Repositories\Home\SchoolRepository as School;
use App\Repositories\Home\CityRepository as City;
use App\Repositories\Home\TagRepository as Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SchoolController extends BaseController
{

    protected $school;
    protected $city;
    protected $tag;

    public function __construct(School $school,City $city,Tag $tag)
    {
        parent::__construct();

        $this->school = $school;
        $this->city = $city;
        $this->tag = $tag;

        view()->share('menu','School');
    }


    /**
     * 高校列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Config::get('home.page_size',10);
        $list = $this->school->getList($params);
        $count = $list['count'] ?? 0;
        $list = $list['list'] ?? [];

        //省份
        $where1['parent'] = 0;
        $province = $this->city->getCityList($where1);

        return view('home.school.index',compact('params','list','count','province'));
    }


    /**
     * 高校详情页
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function detail($id)
    {
        //高校信息
        $data = $this->school->find($id);

        return view('home.school.detail',compact('data'));
    }

}
