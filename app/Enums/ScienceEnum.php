<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class ScienceEnum extends BaseEnum {

    const SCIENCE = 1;
    const LIBERAL = 2;
//    const ART = 3;
//    const SPORT = 4;

    static $desc = array(
        'SCIENCE'=>'理科',
        'LIBERAL'=>'文科',
//        'ART'=>'艺术',
//        'SPORT'=>'体育',
    );

}
