<?php

namespace App\Jobs;

use App\Enums\BeanEnum;
use App\Models\Common\BeanLog;
use App\Models\Common\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Rebate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     * @param $params
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 用户抢单花费玉豆，给上级推广返佣
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        echo '-----------'.date('Y-m-d H:i:s').'更改用户会员等级    BEGIN  ------------'."\n";
        $user_id = isset($this->params['user_id']) ? $this->params['user_id'] : 0;
        $bean_num = isset($this->params['bean_num']) ? $this->params['bean_num'] : 0;
        if($user_id && $bean_num){
            $user_info = User::select('id','username','parent_id')->where('id',$user_id)->first();
            //一代返佣
            if(isset($user_info->parent_id) && !empty($user_info->parent_id)){
                $one_spread = Cache::get(Config::get('common.cache.website_config').'commodity_one_spread_money');
                if(empty($one_spread)){
                    $value = \App\Models\Common\Config::where('only_tag','commodity_one_spread_money')->pluck('value');
                    $one_spread = $value[0] ?? 0;
                    Cache::put(Config::get('common.cache.website_config').'commodity_one_spread_money', $one_spread, 1440);
                }
                $change_bean = round($bean_num*$one_spread/100,2);
                $log = [
                    'user_id' => $user_info->parent_id,
                    'bean' => $change_bean,
                    'type' => BeanEnum::PROFIT,
                    'describe' => '下级会员'.$user_info->username.'抢单，返利'.format_money($change_bean).'个玉豆',
                    'create_time' => time()
                ];
                DB::beginTransaction();
                try{
                    $res1 = User::where('id',$user_info->parent_id)->increment('bean',$change_bean,['update_time' => time()]);
                    $res2 = BeanLog::insert($log);
                    if($res1 && $res2){
                        DB::commit();
                    }
                    DB::rollBack();
                }catch (\Exception $e){
                    DB::rollBack();
                }
                //二代返佣
                $parent_info = User::select('id','username','parent_id')->where('id',$user_info->parent_id)->first();
                if(isset($parent_info->parent_id) && !empty($parent_info->parent_id)){
                    //判断二代的直推是否满足2人，不满足不返
                    $count = User::where('parent_id',$parent_info->parent_id)->count();
                    if($count >= 2){
                        $two_spread = Cache::get(Config::get('common.cache.website_config').'commodity_two_spread_money');
                        if(empty($two_spread)){
                            $value = \App\Models\Common\Config::where('only_tag','commodity_two_spread_money')->pluck('value');
                            $two_spread = $value[0] ?? 0;
                            Cache::put(Config::get('common.cache.website_config').'commodity_two_spread_money', $two_spread, 1440);
                        }
                        $change_bean = round($bean_num*$two_spread/100,2);
                        $log = [
                            'user_id' => $parent_info->parent_id,
                            'bean' => $change_bean,
                            'type' => BeanEnum::PROFIT,
                            'describe' => '下级会员'.$user_info->username.'抢单，返利'.format_money($change_bean).'个玉豆',
                            'create_time' => time()
                        ];
                        DB::beginTransaction();
                        try{
                            $res1 = User::where('id',$parent_info->parent_id)->increment('bean',$change_bean,['update_time' => time()]);
                            $res2 = BeanLog::insert($log);
                            if($res1 && $res2){
                                DB::commit();
                            }
                            DB::rollBack();
                        }catch (\Exception $e){
                            DB::rollBack();
                        }
                    }
                }
            }
        }

//        echo '-----------'.date('Y-m-d H:i:s').'更改用户会员等级    END  ------------'."\n";
    }
}
