<?php
namespace App\Http\Controllers\Admin;


use App\Enums\BasicEnum;
use App\Enums\BeanEnum;
use App\Enums\BoolEnum;
use App\Enums\RegionAgentEnum;
use App\Enums\SpreadEnum;
use App\Models\Common\BeanLog;
use App\Models\Common\City;
use App\Models\Common\Device;
use App\Models\Common\DeviceFee;
use App\Models\Common\RegionAgent;
use App\Models\Common\Spread;
use App\Models\Common\User;
use Illuminate\Support\Facades\DB;

class IndexController extends BaseController
{
	public function index()
    {
        return view('admin.index.index');
    }

    public function homepage()
    {
        //昨日
        $yesterday = date('Y-m-d',strtotime('-1 Day'));
        $yesterday1 = strtotime($yesterday.' 00:00:00');
        $yesterday2 = strtotime($yesterday.' 23:59:59');
        //今日
        $today1 = strtotime(date('Y-m-d'));
        $today2 = time();
        //上月
        $last_month = date('Y-m',strtotime('-1 Month'));
        $last_month1 = strtotime($last_month.'-01 00:00:00');
        $last_month2 = strtotime(date('Y-m').'-01 00:00:00') - 1;
        //用户总数
        $user_count = User::where('delete_time',0)->where('is_robot',BoolEnum::NO)->count();
        //今日注册用户总数
        $user_today = User::where('delete_time',0)->where('is_robot',BoolEnum::NO)
            ->where('create_time','>=',$today1)->where('create_time','<=',$today2)->count();
        //昨日注册用户数量
        $user_yesterday = User::where('delete_time',0)->where('is_robot',BoolEnum::NO)
            ->where('create_time','>=',$yesterday1)->where('create_time','<=',$yesterday2)->count();
        //上月注册用户数量
        $user_last_month = User::where('delete_time',0)->where('is_robot',BoolEnum::NO)
            ->where('create_time','>=',$last_month1)->where('create_time','<=',$last_month2)->count();
        //今日平台玉豆收益
        $bean_today = BeanLog::where('type',BeanEnum::ROB)
            ->where('create_time','>=',$today1)->where('create_time','<=',$today2)->sum('bean');
        $bean_today = $bean_today < 0 ? -1*$bean_today : $bean_today;
        //昨日平台玉豆收益
        $bean_yesterday = BeanLog::where('type',BeanEnum::ROB)
            ->where('create_time','>=',$yesterday1)->where('create_time','<=',$yesterday2)->sum('bean');
        $bean_yesterday = $bean_yesterday < 0 ? -1*$bean_yesterday : $bean_yesterday;
        //上月玉豆收益
        $bean_last_month = BeanLog::where('type',BeanEnum::ROB)
            ->where('create_time','>=',$last_month1)->where('create_time','<=',$last_month2)->sum('bean');
        //平台玉豆总收益
        $bean_total = BeanLog::where('type',BeanEnum::ROB)->sum('bean');
        $bean_total = $bean_total < 0 ? -1*$bean_total : $bean_total;

        $data = [
            'user_count' => $user_count,
            'user_today' => $user_today,
            'user_yesterday' => $user_yesterday,
            'user_last_month' => $user_last_month,
            'bean_total' => $bean_total,
            'bean_today' => $bean_today,
            'bean_yesterday' => $bean_yesterday,
            'bean_last_month' => $bean_last_month,
        ];
        return view('admin.index.homepage',compact('data'));
    }

    /**
     * 数量统计
     */
    public function numberSta()
    {

    }

    /**
     * 用户地图分部
     */
    public function userSpreadSta()
    {
        $city_list = City::where('parent',0)->where('status',BasicEnum::ACTIVE)->get();
        //用户数量统计
        $user_list = User::select([
            DB::raw('count(*) as count'),
            DB::raw('province')
        ])->where('province','>',0)->groupBy('province')->get()->toArray();
        $userList = [];
        if($user_list){
            foreach ($user_list as $v){
                $userList[$v['province']] = $v;
            }
        }
        $provinceList = [];
        $max = 0;
        $tableData1 = [];
        $tableData2 = [];
        $tableData3 = [];
        if($city_list){
            foreach ($city_list as $k => $v){
                $provinceList[$k] = [
                    'name' => $v['title'],
                    'value' => isset($userList[$v['id']]['count']) ? $userList[$v['id']]['count'] : 0
                ];
                if($provinceList[$k]['value'] > $max){
                    $max = $provinceList[$k]['value'];
                }
            }
        }
        //表格数据
        for ($i = 0; $i < count($provinceList) - 1; $i ++){
            for ($j = $i + 1; $j < count($provinceList); $j ++){
                if($provinceList[$i]['value'] < $provinceList[$j]['value']){
                    $temp = $provinceList[$j];
                    $provinceList[$j] = $provinceList[$i];
                    $provinceList[$i] = $temp;
                }
            }
        }
        foreach ($provinceList as $k => $v){
            $v['sort'] = $k + 1;
            if($k >= 0 && $k <= 10){
                $tableData1[] = $v;
            }elseif ($k > 10 && $k <= 21){
                $tableData2[] = $v;
            }else{
                $tableData3[] = $v;
            }
        }
        $data = [
            'province' => $provinceList,
            'max' => $max,
            'tableData1' => $tableData1,
            'tableData2' => $tableData2,
            'tableData3' => $tableData3
        ];
        return $this->ajaxSuccess($data,'OK');
    }

}
