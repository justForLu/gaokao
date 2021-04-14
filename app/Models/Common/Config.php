<?php

namespace App\Models\Common;

use App\Models\Base;

class Config extends Base
{
    // 配置
    protected $table = 'config';

    protected $fillable = ['value','name','describe','only_tag'];


}
