<?php

namespace App\Models\Common;

use App\Models\Base;

class School extends Base
{
    // 高校
    protected $table = 'school';

    protected $fillable = ['name','logo','province','city','area','address','website','phone','email','measure','belong','tag',
        'content','sort','status'];


}
