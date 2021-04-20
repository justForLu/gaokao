<?php

namespace App\Models\Common;

use App\Models\Base;

class Tag extends Base
{
    // 高校标签
    protected $table = 'tag';

    protected $fillable = ['name','shorter','sort','status',];


}
