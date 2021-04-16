<?php

namespace App\Models\Common;

use App\Models\Base;

class Score extends Base
{
    // 高考分数线
    protected $table = 'score';

    protected $fillable = ['province','year','yiben_li','erben_li','sanben_li','dazhuan_li','yiben_wen','erben_wen','sanben_wen',
        'dazhuan_wen'];


}
