<?php

namespace App\Models\Common;

use App\Models\Base;

class Feedback extends Base
{
    // 反馈表
    protected $table = 'feedback';

    protected $fillable = ['name','mobile','content','status'];

}
