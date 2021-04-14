<?php

namespace App\Models\Common;

use App\Models\Base;

class Stay extends Base
{
    // 驻场员表
    protected $table = 'stay';

    protected $fillable = ['name','mobile','wechat','email','province','city','area','address','status'];

}
