<?php

namespace App\Models\Common;

use App\Models\Base;

class Category extends Base
{
    // 商品分类表
    protected $table = 'category';

    protected $fillable = ['name','image','status','sort'];



}
