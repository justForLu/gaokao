<?php

namespace App\Models\Common;

use App\Models\Base;

class Supplier extends Base
{
    // 供应商信息
    protected $table = 'supplier';

    protected $fillable = ['name','agent_name','mobile','phone','email','province','city','area','address','join_time',
        'supply_num','star','status'];

}
