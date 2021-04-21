<?php

namespace App\Models\Common;

use App\Models\Base;

class MajorLine extends Base
{
    // 专业录取分数线
    protected $table = 'major_line';

    protected $fillable = ['school_id','major_id','province','year','batch','science','max_score','avg_score','min_score',
        'min_rank','recruit_num','sign_num','enter_num'];

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
