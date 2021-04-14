<?php

return [
    'login_code' => 'login:code',   //登录验证码
    'reg_code' => 'reg:code',   //注册验证码
    'forget_code' => 'forget:code', //忘记密码验证码
    'update_pwd_code' => 'upd:pwd:code',    //修改密码验证码
    'pay_pwd_code' => 'pay:pwd:code',   //修改支付密码验证码
    'user_bank_code' => 'user:bank:code',   //修改个人银行、支付宝信息验证码
    'user_token' => 'u:t:',  //用户登录token
    'user_id' => 'u:id:',  //记录用户登录的token
    'uploadHeadImg' => '/upload/api/headImg',
    'uploadCarImg' => '/upload/api/carImg',
    'uploadCodeImg' => '/upload/api/codeImg',
    'uploadBeanImg' => '/upload/api/beanImg',
    'uploadBillImg' => '/upload/api/billImg',
    'document_root' => $_SERVER['DOCUMENT_ROOT']
];
