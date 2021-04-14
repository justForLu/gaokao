<?php

namespace App\Models\Common;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const CREATED_AT='create_time';
    const UPDATED_AT='update_time';
    const DELETED_AT='delete_time';

    protected $dateFormat = 'U';
    // 用户
    protected $table = 'user';

    protected $fillable = ['id','openid','username','password','salt','head_img','real_name','id_card','nickname','province',
        'city','area','address','mobile','email','money','status','login_time','login_ip','login_times','account_name',
        'account_no','bank_name','role_id','agent_id','business_id','stay_id'];


}
