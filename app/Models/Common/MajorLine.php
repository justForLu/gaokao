<?php

namespace App\Models\Common;

use App\Models\Base;

class MajorLine extends Base
{
    // 专业录取分数线
    protected $table = 'major_line';

    protected $fillable = ['school_id','major_id','province','year','batch','science','max_score','avg_score','min_score',
        'min_rank','recruit_num','sign_num','enter_num'];


}
