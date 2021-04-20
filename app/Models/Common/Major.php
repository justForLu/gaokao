<?php

namespace App\Models\Common;

use App\Models\Base;

class Major extends Base
{
    // 高校专业
    protected $table = 'major';

    protected $fillable = ['school_id','category_id','name','type','grade','edu_system','content'];


}
