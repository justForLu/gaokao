<?php
namespace App\Http\Controllers\Home;

use App\Enums\BasicEnum;
use App\Enums\BatchEnum;
use App\Enums\MajorEnum;
use App\Enums\MajorTypeEnum;
use App\Enums\ScienceEnum;
use App\Models\Common\Category;
use App\Models\Common\EnterLine;
use App\Models\Common\Major;
use App\Models\Common\MajorLine;
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
            $tag_arr = array_diff(array_unique(explode(',',$tag)),['']);
            $tag_list = [];
            if($tag_arr){
                $tag_list = \App\Models\Common\Tag::whereIn('id',$tag_arr)->pluck('shorter','id');
            }
            //高校省市
            $province_id = array_unique(array_column($list,'province'));
            $city_id = array_unique(array_column($list,'city'));
            $area_id = array_unique(array_column($list,'area'));
            $region_id = array_diff(array_merge($province_id,$city_id,$area_id),[0]);
            $region_list = [];
            if($region_id){
                $region_list = \App\Models\Common\City::whereIn('id',$region_id)->pluck('title','id');
            }

            foreach ($list as &$v){
                //省市
                $province_name = $region_list[$v['province']] ?? '';
                if(in_array($v['province'],[1,24,26,31])){
                    $city_name = $region_list[$v['area']] ?? '';
                }else{
                    $city_name = $region_list[$v['city']] ?? '';
                }
                $v['region'] = $province_name.$city_name;
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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function detail($id,Request $request)
    {
        $params = $request->all();
        $nav = $params['nav'] ?? 'desc';
        //高校信息
        $data = $this->school->find($id);
        $data->content = htmlspecialchars_decode($data->content);
        //高校省市
        $region_id = array_diff([$data->province,$data->city,$data->area],[0]);
        $region_list = [];
        if($region_id){
            $region_list = \App\Models\Common\City::whereIn('id',$region_id)->pluck('title','id');
        }
        $province_name = $region_list[$data->province] ?? '';
        if(in_array($data->province,[1,24,26,31])){
            $city_name = $region_list[$data->area] ?? '';
        }else{
            $city_name = $region_list[$data->city] ?? '';
        }
        $data->region = $province_name.$city_name;
        //高校标签
        $tag_arr = array_diff(explode(',',$data->tag),['']);
        $tag_list = [];
        if($tag_arr){
            $tag_list = \App\Models\Common\Tag::whereIn('id',$tag_arr)->pluck('name','id');
        }
        $temp_arr = [];
        if($tag_arr){
            foreach ($tag_arr as $tag_id){
                if(isset($tag_list[$tag_id]) && !empty($tag_list[$tag_id])){
                    $temp_arr[] = $tag_list[$tag_id];
                }
            }
        }
        $data->tag_arr = $temp_arr;
        //热门高校
        $where3['status'] = BasicEnum::ACTIVE;
        $where3['limit'] = 10;
        $field3 = ['id','name'];
        $school_hot = $this->school->getAllList($where3,$field3);
        //省份
        $where1['parent'] = 0;
        $province = $this->city->getCityList($where1);
        //年份
        $the_year = date('Y');
        $year_list = [];
        for ($i = 0; $i <=10; $i++){
            $year_list[] = $the_year - $i;
        }
        //省录取分数线默认数据
        $province_province = $params['province_province'] ?? 18;    //默认内蒙古
        $province_year = $params['province_year'] ?? (date('Y') - 1);
        $province_science = $params['province_science'] ?? 1;   //默认理科
        $province_curr = $params['province_curr'] ?? 1;   //当前页
        $province_count = 0;
        $province_line_list = [];
        //各专业录取分数线默认数据
        $line_province = $params['line_province'] ?? 18;    //默认内蒙古
        $line_year = $params['line_year'] ?? (date('Y') - 1);
        $line_science = $params['line_science'] ?? 1;   //默认理科
        $line_batch = $params['line_batch'] ?? 0;   //批次
        $line_curr = $params['line_curr'] ?? 1;   //当前页
        //查找该校拥有的批次
        $batch = [];
        $line_count = 0;
        $major_line_list = [];
        //高校专业
        $subject = [];  //学科评估
        $country_major = [];    //国家特色专业
        $main_major = [];   //重点学科
        $king_major = [];   //本校王牌专业
        $major = [];    //按分类的专业列表
        if($nav == 'major'){
            $major_list = Major::where('school_id',$id)->orderBy('sort','DESC')->get()->toArray();
            if(!empty($major_list)){
                //学科分类
                $category_id = array_unique(array_column($major_list,'category_id'));
                $category_list = [];
                if($category_id){
                    $category_list = Category::whereIn('id',$category_id)->pluck('name','id');
                }
                foreach ($major_list as $v){
                    //学科评估
                    if($v['grade'] == MajorEnum::ONE){
                        $subject[MajorEnum::ONE]['num'] = isset($subject[MajorEnum::ONE]['num']) ? $subject[MajorEnum::ONE]['num'] + 1 : 1;
                        $subject[MajorEnum::ONE]['name'] = 'A+学科';
                    }elseif ($v['grade'] == MajorEnum::TWO){
                        $subject[MajorEnum::TWO]['num'] = isset($subject[MajorEnum::TWO]['num']) ? $subject[MajorEnum::TWO]['num'] + 1 : 1;
                        $subject[MajorEnum::TWO]['name'] = 'A学科';
                    }elseif ($v['grade'] == MajorEnum::THREE){
                        $subject[MajorEnum::THREE]['num'] = isset($subject[MajorEnum::THREE]['num']) ? $subject[MajorEnum::THREE]['num'] + 1 : 1;
                        $subject[MajorEnum::THREE]['name'] = 'A-学科';
                    }elseif ($v['grade'] == MajorEnum::FOUR){
                        $subject[MajorEnum::FOUR]['num'] = isset($subject[MajorEnum::FOUR]['num']) ? $subject[MajorEnum::FOUR]['num'] + 1 : 1;
                        $subject[MajorEnum::FOUR]['name'] = 'B+学科';
                    }elseif ($v['grade'] == MajorEnum::FIVE){
                        $subject[MajorEnum::FIVE]['num'] = isset($subject[MajorEnum::FIVE]['num']) ? $subject[MajorEnum::FIVE]['num'] + 1 : 1;
                        $subject[MajorEnum::FIVE]['name'] = 'B学科';
                    }elseif ($v['grade'] == MajorEnum::SIX){
                        $subject[MajorEnum::SIX]['num'] = isset($subject[MajorEnum::SIX]['num']) ? $subject[MajorEnum::SIX]['num'] + 1 : 1;
                        $subject[MajorEnum::SIX]['name'] = 'B-学科';
                    }elseif ($v['grade'] == MajorEnum::SEVEN){
                        $subject[MajorEnum::SEVEN]['num'] = isset($subject[MajorEnum::SEVEN]['num']) ? $subject[MajorEnum::SEVEN]['num'] + 1 : 1;
                        $subject[MajorEnum::SEVEN]['name'] = 'C+学科';
                    }elseif ($v['grade'] == MajorEnum::EIGHT){
                        $subject[MajorEnum::EIGHT]['num'] = isset($subject[MajorEnum::EIGHT]['num']) ? $subject[MajorEnum::EIGHT]['num'] + 1 : 1;
                        $subject[MajorEnum::EIGHT]['name'] = 'C学科';
                    }elseif ($v['grade'] == MajorEnum::NINE){
                        $subject[MajorEnum::NINE]['num'] = isset($subject[MajorEnum::NINE]['num']) ? $subject[MajorEnum::NINE]['num'] + 1 : 1;
                        $subject[MajorEnum::NINE]['name'] = 'C-学科';
                    }
                    //国家特色专业
                    if($v['type'] == MajorTypeEnum::COUNTRY){
                        $country_major[] = $v['name'];
                    }
                    //重点学科
                    if($v['type'] == MajorTypeEnum::IMPORTANT){
                        $main_major[] = $v['name'];
                    }
                    //本校王牌专业
                    if($v['type'] == MajorTypeEnum::TRUMP){
                        $king_major[] = $v['name'];
                    }
                    //专业列表
                    $major[$v['category_id']]['arr'][] = ['id' => $v['id'],'name' => $v['name']];
                    $major[$v['category_id']]['category_name'] = $category_list[$v['category_id']] ?? '';
                }
            }
        }elseif ($nav == 'province'){
            //各省录取分数线总数
            $province_count = EnterLine::where('school_id',$id)->where('province',$province_province)
                ->where('year',$province_year)->where('science',$province_science)->count();
            //省录取分数线数据
            $offset1 = ($province_curr - 1)*10;
            $province_line_list = EnterLine::where('school_id',$id)->where('province',$province_province)
                ->where('year',$province_year)->where('science',$province_science)
                ->offset($offset1)->limit(10)->get();
            if(!empty($province_line_list)){
                foreach ($province_line_list as &$p_val){
                    $p_val['batch_name'] = BatchEnum::getDesc($p_val['batch']);
                    $p_val['science_name'] = ScienceEnum::getDesc($p_val['science_name']);
                }
            }

        }elseif ($nav == 'line'){
            //查找该校拥有的批次
            $batch_list = MajorLine::where('school_id',$id)->orderBy('batch','ASC')->pluck('batch');
            if(!empty($batch_list)){
                $batch_arr = ['1' => '提前批','2' => '本科一批','3' => '本科二批','4' => '本科三批','5' => '大专批'];
                foreach ($batch_list as $b_v){
                    if(in_array($b_v,[1,2,3,4,5])){
                        $batch[$b_v] = ['id' => $b_v,'name' => $batch_arr[$b_v]];
                    }
                }
            }
            if($line_batch == 0){
                $batchFirst = current($batch);
                $line_batch = $batchFirst['id'];
            }
            //各专业录取总数
            $line_count = MajorLine::where('school_id',$id)->where('province',$line_province)
                ->where('year',$line_year)->where('science',$line_science)->where('batch',$line_batch)->count();
            $offset2 = ($line_curr - 1)*10;
            $major_line_list = MajorLine::where('school_id',$id)->where('province',$line_province)
                ->where('year',$line_year)->where('science',$line_science)->where('batch',$line_batch)
                ->offset($offset2)->limit(10)->get()->toArray();
            if(!empty($major_line_list)){
                //专业名称
                $major_ids = array_diff(array_unique(array_column($major_line_list,'major_id')),[0]);
                $majorList = [];
                if($major_ids){
                    $majorList = Major::whereIn('id',$major_ids)->pluck('name','id');
                }
                foreach ($major_line_list as &$m_val){
                    $m_val['major_name'] = $majorList[$m_val['major_id']] ?? '';
                    $m_val['batch_name'] = BatchEnum::getDesc($m_val['batch']);
                }
            }
        }

        return view('home.school.detail',compact('data','nav','subject','country_major','main_major',
            'major','king_major','school_hot','province','year_list','province_province','province_year','province_science',
            'province_count','province_line_list','line_province','line_year','line_science','line_batch','line_count',
            'major_line_list','batch'));
    }

}
