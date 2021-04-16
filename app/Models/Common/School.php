<?php

namespace App\Models\Common;

use App\Models\Base;

class School extends Base
{
    // 高校
    protected $table = 'school';

    protected $fillable = ['name','province','city','area'];


}
