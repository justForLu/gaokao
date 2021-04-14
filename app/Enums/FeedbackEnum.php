<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class FeedbackEnum extends BaseEnum {

    // 意见反馈状态

    const HANDLE = 1;
    const DOING = 2;
    const DONE = 3;

    static $desc = array(
        'HANDLE' => '待处理',
        'DOING' => '处理中',
        'DONE' => '处理完成',
    );
}
