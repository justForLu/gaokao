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
        if($list){
            //高校标签
            $tag = array_column($list,'tag');
            $tag = implode(',',$tag);
            $tag_arr = array_diff(array_unique(explode(',',$tag)),[0]);
            $tag_list = [];
            if($tag_arr){
                $tag_list = \App\Models\Common\Tag::whereIn('id',$tag_arr)->pluck('shorter','id');
            }
            foreach ($list as &$v){
                //高校标签
                $tag_ids = array_diff(explode(',',$v['tag']),[0]);
                $temp_arr = [];
                if($tag_ids){
                    foreach ($tag_ids as $id){
                        if(isset($tag_list[$id]) && !empty($tag_list[$id])){
                            $temp_arr[] = $tag_list[$id];
                        }
                    }
                }
                $v['tag_arr'] = $temp_arr;
            }
        }

        //省份
        $where1['parent'] = 0;
        $province = $this->city->getCityList($where1);
        //高校标签
        $where2['status'] = BasicEnum::ACTIVE;
        $where2['limit'] = 100;
        $tag = $this->tag->getList($where2);
        //热门高校
        $where3['status'] = BasicEnum::ACTIVE;
        $where3['limit'] = 10;
        $field3 = ['id','name'];
        $school_hot = $this->school->getAllList($where3,$field3);

        return view('home.school.index',compact('params','list','count','province','tag','school_hot'));
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
