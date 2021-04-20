<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class CategoryEnum extends BaseEnum {

    const ARTICLE = 1;
    const MAJOR = 2;

    static $desc = array(
        'ARTICLE'=>'文章',
        'MAJOR'=>'专业',
    );

}
