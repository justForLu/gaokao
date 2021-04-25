<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Enums\QuestionEnum;
use App\Models\Common\Collect;
use App\Repositories\Home\Criteria\ShopCriteria;
use App\Repositories\Home\ShopRepository as Shop;
use App\Repositories\Home\CityRepository as City;
use App\Repositories\Home\RecruitRepository as Recruit;
use App\Repositories\Home\FactoryRepository as Factory;
use App\Repositories\Home\QuestionRepository as Question;
use App\Repositories\Home\AnswerRepository as Answer;
use App\Repositories\Home\EnrollRepository as Enroll;
use App\Repositories\Home\ShopRecruitRepository as ShopRecruit;
use App\Repositories\Home\LabelRepository as Label;
use App\Repositories\Home\PositionRepository as Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ShopController extends BaseController
{

    protected $shop;
    protected $city;
    protected $recruit;
    protected $factory;
    protected $question;
    protected $answer;
    protected $enroll;
    protected $shop_recruit;
    protected $label;
    protected $position;

    public function __construct(Shop $shop, City $city, Recruit $recruit, Factory $factory, Question $question, Answer $answer,
                                Enroll $enroll, ShopRecruit $shop_recruit, Label $label, Position $position)
    {
        parent::__construct();

        $this->shop = $shop;
        $this->city = $city;
        $this->recruit = $recruit;
        $this->factory = $factory;
        $this->question = $question;
        $this->answer = $answer;
        $this->enroll = $enroll;
        $this->shop_recruit = $shop_recruit;
        $this->label = $label;
        $this->position = $position;

        view()->share('menu','Shop');
    }


    /**
     * 门店列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index(Request $request)
    {
        $params = $request->all();
        if($params){
            foreach ($params as &$param){
                $param = trim($param);
            }
        }

        $this->shop->pushCriteria(new ShopCriteria($params));

        $list = $this->shop->paginate(Config::get('home.page_size',10));
        if ($list){
            //处理招聘企业
            foreach ($list as &$val){
                $where1 = [
                    'shop_id' => $val['id']
                ];

                $factory_name = $this->shop_recruit->getShopFactory($where1);

                $val['factory_name'] = implode('，',$factory_name);
            }
        }

        //省份
        $where2['parent'] = 0;
        $province = $this->city->getCityList($where2);

        //默认城市（默认河南）
        $where3['parent'] = isset($params['province_id']) && $params['province_id'] > 0 ? $params['province_id'] : 10;   //河南的ID是10
        $city = $this->city->getCityList($where3);

        //大家想去
        $where4['is_recommend']['EQ'] = BasicEnum::ACTIVE;
        $recruit = $this->recruit->getList('*',$where4,5,['factory']);

        //热门招聘企业
        $where6['is_recommend']['EQ'] = BasicEnum::ACTIVE;
        $recruit_hot = $this->recruit->getList('*',$where6,5,['factory']);

        //报名排行榜
        $where7['is_recommend']['EQ'] = BasicEnum::ACTIVE;
        $factory_top = $this->factory->getList('*',$where6,10);
        if($factory_top){
            $factory_ids = array_column($factory_top,'id');
            $where_r['factory_id']['IN'] = $factory_ids;
            $recruit_fac = $this->recruit->getList(['id','factory_id'],$where_r);
            $recruit_fac = set_index($recruit_fac,'factory_id');
            foreach ($factory_top as &$v){
                $v['recruit_id'] = $recruit_fac[$v['id']]['id'] ?? 0;
            }
        }


        return view('home.shop.index',compact('params','list','province','city','recruit','recruit_hot'));
    }


    /**
     * 门店详情页
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function detail($id)
    {
        //门店信息
        $data = $this->shop->find($id);
        //门店图集
        $data->picture_path = array_values(FileController::getFilePath($data->picture));
        $count_pic = count($data->picture_path);
        //报名人数
        $where1 = [
            'shop_id' => $id
        ];
        $count_enroll = $this->enroll->getCount($where1);
        //招聘企业
        $where2 = [
            'shop_id' => $id
        ];
        $list_s_r = $this->shop_recruit->getList($where2);
        $recruit_id = array_unique(array_column($list_s_r,'recruit_id'));
        $recruit_list  = [];
        if($recruit_id){
            $where_s_r['id']['IN'] = $recruit_id;
            $recruit_list = $this->recruit->getList('*',$where_s_r,0,['factory']);
        }
        //处理福利标签
        if($recruit_list){
            foreach ($recruit_list as &$v){
                if($v['factory']['label']){
                    //标签
                    $label_ids = explode(',',$v['factory']['label']);
                    $where['id'] = $label_ids;

                    $v['label_arr'] = $this->label->getList($where);
                }
                if($v['posts']){
                    //职位
                    $posts = explode(',',$v['posts']);

                    $where_p['id'] = $posts;

                    $v['posts_arr'] = $this->position->getList($where_p);
                }
            }
        }

        //问答信息
        $where3 = [
            'shop_id' => $id,
            'type' => QuestionEnum::SHOP,
        ];
        $question = $this->question->getList($where3);
        $count_question = count($question);
        if ($question){
            foreach ($question as &$v){
                $where_a['question_id'] = $v['id'];
                $list_a = $this->answer->getList($where_a);
                $v['answer'] = $list_a;
            }
        }
        //大家想去
        $where4['is_recommend']['EQ'] = BasicEnum::ACTIVE;
        $recruit = $this->recruit->getList('*',$where4,5,['factory']);

        $user_id = isset($this->userInfo->id) ? $this->userInfo->id : 0;
        //收藏信息
        $is_collect = Collect::where('user_id',$user_id)->where('type',2)->where('shop_id',$id)->count();

        return view('home.shop.detail',compact('data','count_pic','count_enroll','recruit_list','question',
            'count_question','recruit','user_id','is_collect'));
    }

}
