<?php

namespace App\Models\Common;

use App\Models\Base;

class Business extends Base
{
    // 业务员
    protected $table = 'business';

    protected $fillable = ['name','mobile','wechat','email','province','city','area','address','status'];



}
