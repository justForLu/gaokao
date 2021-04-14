<?php

namespace App\Models\Common;

use App\Models\Base;

class RebateLog extends Base
{
    // 返费记录
    protected $table = 'rebate_log';

    protected $fillable = ['name','mobile','wechat','email','province','city','area','address','status'];



}
