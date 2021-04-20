<?php

namespace App\Models\Common;

use App\Models\Base;

class EnterLine extends Base
{
    // 高校录取分数线
    protected $table = 'enter_line';

    protected $fillable = ['school_id','province','year','science','batch','max_score','avg_score','min_score','min_rank',
        'control_line'];

    /**
     * 高校
     */
    public function school(){
        return $this->hasOne(School::class,'id','school_id');
    }

    /**
     * 省份
     */
    public function province(){
        return $this->hasOne(City::class,'id','province');
    }

}
