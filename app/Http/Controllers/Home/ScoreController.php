<?php
namespace App\Http\Controllers\Home;

use App\Repositories\Home\ScoreRepository as Score;
use App\Repositories\Home\CityRepository as City;
use Illuminate\Http\Request;

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
        //省份
        $where1['parent'] = 0;
        $province = $this->city->getCityList($where1);

        return view('home.score.index',compact('province'));
    }

    /**
     * 获取列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $params = $request->all();

        $result = $this->score->getList($params);

        return $this->ajaxSuccess($result);
    }
}
