<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class MajorTypeEnum extends BaseEnum {

    //专业类型
    const COUNTRY = 1;
    const IMPORTANT = 2;
    const TRUMP = 3;

    static $desc = array(
        'COUNTRY'=>'国家特色专业',
        'IMPORTANT'=>'重点学科专业',
        'TRUMP'=>'本校王牌专业',
    );

}
