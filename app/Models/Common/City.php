<?php

namespace App\Models\Common;

use App\Models\Base;

class City extends Base
{
    // 城市
    protected $table = 'city';

    protected $fillable = ['title','parent','topid','path','grade','sort','status'];



}
