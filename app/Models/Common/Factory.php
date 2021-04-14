<?php

namespace App\Models\Common;

use App\Models\Base;

class Factory extends Base
{
    // 工厂
    protected $table = 'factory';

    protected $fillable = ['name','province','city','area','address','business_id','stay_id'];



}
