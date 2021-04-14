<?php

namespace App\Enums;

/**
 * @method static BaseEnum ENUM()
 */
class UserEnum extends BaseEnum {

    //用户角色
    const REGISTER = 0;
    const GENERAL = 1;
    const STAY = 2;
    const AGENT = 3;

    static $desc = array(
        'REGISTER' => '注册用户',
        'GENERAL' => '普通用户',
        'STAY' => '驻场',
        'AGENT' => '代理',
    );
}
