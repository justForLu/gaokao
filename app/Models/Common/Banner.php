<?php

namespace App\Models\Common;

use App\Models\Base;

class Banner extends Base
{
    // 轮播
    protected $table = 'banner';

    protected $fillable = ['title','image','url','position','sort','status'];



}
