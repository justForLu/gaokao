<?php

namespace App\Models\Common;

use App\Models\Base;

class Article extends Base
{
    // 文章
    protected $table = 'article';

    protected $fillable = ['title','type','content','sort','status'];



}