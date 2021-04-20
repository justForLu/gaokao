<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class MajorEnum extends BaseEnum {

    //学科等级
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const FOUR = 4;
    const FIVE = 5;
    const SIX = 6;
    const SEVEN = 7;
    const EIGHT = 8;
    const NINE = 9;

    static $desc = array(
        'ONE'=>'A+',
        'TWO'=>'A',
        'THREE'=>'A-',
        'FOUR'=>'B+',
        'FIVE'=>'B',
        'SIX'=>'B-',
        'SEVEN'=>'C+',
        'EIGHT'=>'C',
        'NINE'=>'C-',
    );

}
