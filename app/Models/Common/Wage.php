<?php

namespace App\Models\Common;

use App\Models\Base;

class Wage extends Base
{
    // 薪资记录
    protected $table = 'wage';

    protected $fillable = ['user_id','agent_id','factory_area','work_type','wd_id','sap_id','username','id_card','entry_time',
        'leavedate','quit_reason','social_insurance','person_tax','hydropower','carry_over','out_hour','overtime15',
        'overtime20','overtime30','lack_work','matter_vacation','late_early','sick_leave','hour_total','hour_train',
        'hour_settle','agent_title','interview_time','out_time','hour_money','period_end','is_rebate','train_wage',
        'hour_formal','formal_wage','hourly_wage','business_insure','negative','mend_money','subsidy','surplus_agent',
        'deduct_money','remark'];

}
