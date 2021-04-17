<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class FeedbackEnum extends BaseEnum {

    // 意见反馈状态
    const NO = 0;
    const DONE = 1;

    static $desc = array(
        'NO' => '待处理',
        'DONE' => '已处理',
    );
}
