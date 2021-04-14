<?php

namespace App\Jobs;

use App\Models\Common\Grade;
use App\Models\Common\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserGrade implements ShouldQueue
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
     * 会员注册判断是否需要更改用户等级
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        echo '-----------'.date('Y-m-d H:i:s').'更改用户会员等级    BEGIN  ------------'."\n";
        $user_id = isset($this->params['user_id']) ? $this->params['user_id'] : 0;
        //会员等级
        $grade = Grade::orderBy('id','ASC')->get();
        //查找该会员下的直推数量和团队数量
        if($user_id){
            //查找该会员所有上级，检查是否需要更改等级
            $user_info = User::select('invite_path')->where('id',$user_id)->first();
            if(isset($user_info['invite_path']) && !empty($user_info['invite_path'])){
                //所有上级的用户ID数组
                $path_arr = explode(',',$user_info['invite_path']);
                if($path_arr){
                    foreach ($path_arr as $v){
                        if($v > 0){
                            $parent_info = User::where('id',$v)->first();
                            if(!empty($parent_info)){
                                //直推数量
                                $direct_num = User::where('parent_id',$v)->count();
                                //团队数量
                                $team_num = User::where('invite_path','LIKE','%,'.$v.',%')->count();
                                //查询属于哪个会员等级
                                $user_grade = 0;
                                foreach ($grade as $val){
                                    if($direct_num >= $val['direct_num'] && $team_num >= $val['team_num']){
                                        $user_grade = $val['id'];
                                    }
                                }
                                //如果会员等级变高了，则执行修改会员等级操作
                                if($user_grade > $parent_info->grade){
                                    $data = [
                                        'grade' => $user_grade,
                                        'update_time' => time()
                                    ];
                                    User::where('id',$v)->update($data);
                                }
                            }
                        }
                    }
                }
            }
        }

//        echo '-----------'.date('Y-m-d H:i:s').'更改用户会员等级    END  ------------'."\n";
    }
}
