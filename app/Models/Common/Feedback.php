<?php

namespace App\Models\Common;

use App\Models\Admin\Manager;
use App\Models\Base;

class Feedback extends Base
{
    // 反馈表
    protected $table = 'feedback';

    protected $fillable = ['name','mobile','email','content','status','remark','admin_id','deal_time'];

    /**
     * 操作人
     */
    public function manager(){
        return $this->hasOne(Manager::class,'id','admin_id');
    }
}
