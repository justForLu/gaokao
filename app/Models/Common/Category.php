<?php

namespace App\Models\Common;

use App\Models\Base;

class Category extends Base
{
    // 分类表
    protected $table = 'category';

    protected $fillable = ['name','image','type','status','sort'];



}
