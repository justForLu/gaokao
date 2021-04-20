<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class BatchEnum extends BaseEnum {

    const ZERO = 1;
    const ONE = 2;
    const TWO = 3;
    const THREE = 4;
    const FOUR = 5;

    static $desc = array(
        'ZERO'=>'提前批',
        'ONE'=>'本科一批',
        'TWO'=>'本科二批',
        'THREE'=>'本科三批',
        'FOUR'=>'专科批',
    );

}
