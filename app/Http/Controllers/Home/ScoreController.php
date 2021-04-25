<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Repositories\Home\ScoreRepository as Score;
use App\Repositories\Home\CityRepository as City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ScoreController extends BaseController
{
    protected $score;
    protected $city;

    public function __construct(Score $score,City $city)
    {
        parent::__construct();

        $this->score = $score;
        $this->city = $city;

        view()->share('menu','Score');
    }

    /**
     * 查分数线列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index(Request $request)
    {
        $params = $request->all();
        $params['limit'] = Config::get('home.page_size',10);
        $list = $this->score->getList($params);
        $count = $list['count'] ?? 0;
        $list = $list['list'] ?? [];

        //省份
        $where1['parent'] = 0;
        $province = $this->city->getCityList($where1);

        return view('home.score.index',compact('params','list','count'));
    }


    /**
     * 查分数线详情页
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function detail($id, Request $request)
    {
        $data = $this->score->find($id);

        return view('home.score.detail',compact('data'));
    }
}
