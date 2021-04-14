<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => ['api.switch'],'namespace' => 'Api'], function (){
    Route::get('index','IndexController@index');
    Route::post('login','LoginController@login');
    Route::post('register','LoginController@register');
    Route::post('/user/forget_pwd','UserController@forgetPwd');
    //短信
    Route::post('/sms/login_code','SmsController@loginCode');
    Route::post('/sms/register_code','SmsController@registerCode');
    Route::post('/sms/forget_pwd_code','SmsController@forgetPwdCode');
    //城市
    Route::get('/city/get_list','CityController@getList');
    Route::get('/city/list_by_city','CityController@getListByCity');
    //配置
    Route::get('/config/get_reg_privacy','ConfigController@getRegPrivacy');
    Route::get('/config/get_notice','ConfigController@getNotice');
    Route::get('/config/get_icp','ConfigController@getIcp');
    Route::get('/config/get_custom_tel','ConfigController@getCustomTel');
    Route::get('/config/android_app','ConfigController@androidApp');
    //banner
    Route::get('/banner/get_list','BannerController@getList');
    Route::get('/oil/get_oil_price','OilController@getOilPrice');
    //文章
    Route::get('/article/get_list','ArticleController@getList');
    Route::get('/article/get_notice_list','ArticleController@getNoticeList');
    Route::get('/article/detail','ArticleController@getDetail');
    //分类
    Route::get('/category/get_list','CategoryController@getList');
    //商品
    Route::get('/goods/get_list','GoodsController@getList');
    Route::get('/goods/detail','GoodsController@detail');
    //抢单相关
    Route::get('/slot/get_list','SlotController@getList');
    Route::get('/commodity/get_list','CommodityController@getList');

    //玉豆相关
    Route::get('/bean/get_list','BeanController@getList');
    //支付回调
    Route::post('/notify/notify','NotifyController@notify');
    Route::post('/notify/wechat_notify','NotifyController@wechatNotify');

    Route::group(['middleware' => ['api.auth']], function(){
        Route::get('logout','LoginController@logout');
        //图片上传
        Route::post('/file/head_img','FileController@uploadHeadImg');
        Route::post('/file/car_img','FileController@uploadCarImg');
        Route::post('/file/code_img','FileController@uploadCodeImg');
        //短信
        Route::post('/sms/update_pwd_code','SmsController@updatePwdCode');
        Route::post('/sms/pay_pwd_code','SmsController@payPwdCode');
        Route::post('/sms/user_bank_code','SmsController@userBankCode');
        //用户
        Route::get('/user/get_user','UserController@getUser');
        Route::post('/user/update_pwd','UserController@updatePwd');
        Route::post('/user/update_pay_pwd','UserController@updatePayPwd');
        Route::post('/user/update_user','UserController@updateUser');
        Route::get('/user/get_team','UserController@getTeam');
        Route::get('/user/get_invite_code','UserController@getInviteCode');
        Route::get('/user/get_profit','UserController@getProfit');
        //玉豆相关
        Route::post('/bean/sell_bean','BeanController@sellBean');
        Route::post('/bean/give_bean','BeanController@giveBean');
        Route::post('/bean/cancel_bean','BeanController@cancelBean');
        Route::post('/bean_order/order_bean','BeanOrderController@orderBean');
        Route::get('/bean_order/get_list','BeanOrderController@getList');
        Route::get('/bean_order/get_bean','BeanOrderController@getBean');
        Route::post('/bean_order/pay_bean','BeanOrderController@payBean');
        Route::post('/bean_order/confirm_bean','BeanOrderController@confirmBean');
        Route::post('/bean_order/cancel_bean','BeanOrderController@cancelBean');
        Route::get('/bean_log/get_list','BeanLogController@getList');
        Route::post('/file/bean_img','FileController@uploadBeanImg');   //豆交所上传汇款凭证
        //抢单相关
        Route::post('/commodity/get_bill','CommodityController@getBill');
        Route::post('/file/bill_img','FileController@uploadBillImg');   //抢单上传汇款凭证
        Route::post('/commodity/rob_bill','CommodityController@robBill');
        Route::post('/commodity/pay_bill','CommodityController@payBill');
        Route::post('/commodity/confirm_bill','CommodityController@confirmBill');
        Route::post('/commodity/cancel_bill','CommodityController@cancelBill');
        Route::get('/commodity/get_bill_list','CommodityController@getBillList');
        //用户地址
        Route::get('/user_address/get_list','UserAddressController@getList');
        Route::post('/user_address/add','UserAddressController@addAddress');
        Route::post('/user_address/update','UserAddressController@updateAddress');
        Route::get('/user_address/detail/{id}','UserAddressController@detail');
        Route::get('/user_address/delete/{id}','UserAddressController@deleteAddress');
        //支付方式
        Route::get('/payment/get_list','PaymentController@getList');
        //平台商品订单
        Route::get('/order/get_list','OrderController@getList');
        Route::get('/order/detail','OrderController@detail');
        Route::post('/order/add_order','OrderController@addOrder');
        Route::post('/order/pay','OrderController@pay');
    });
});
