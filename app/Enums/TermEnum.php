<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class TermEnum extends BaseEnum {

    //终端
    const PC = 1;
    const WAP = 2;

    static $desc = array(
        'PC' => 'PC端',
        'WAP' => '移动端',
    );
}
