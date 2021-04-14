<?php

namespace App\Models\Common;

use App\Models\Base;

class Supply extends Base
{
    // 代理商输送记录
    protected $table = 'supply';

    protected $fillable = ['agent_id','supplier_name','agent_name','agent_mobile','worker_name','id_card','factory',
        'out_time','entry_time','leavedate','work_day','hourly_wage','type','rebate_money','paid_money','surplus_money',
        'rebate_explain','draw_money','car_fee','car_subsidy','remark'];

}
