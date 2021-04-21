<?php

namespace App\Models\Common;

use App\Models\Base;

class Major extends Base
{
    // 高校专业
    protected $table = 'major';

    protected $fillable = ['school_id','category_id','name','type','grade','edu_system','content','sort'];

    /**
     * 高校
     */
    public function school(){
        return $this->hasOne(School::class,'id','school_id');
    }

    /**
     * 分类
     */
    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
}
