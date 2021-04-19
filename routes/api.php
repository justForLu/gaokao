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
    Route::get('/config/get_icp','ConfigController@getIcp');
    Route::get('/config/get_custom_tel','ConfigController@getCustomTel');
    //banner
    Route::get('/banner/get_list','BannerController@getList');
    Route::get('/oil/get_oil_price','OilController@getOilPrice');
    //文章
    Route::get('/article/get_list','ArticleController@getList');
    Route::get('/article/get_notice_list','ArticleController@getNoticeList');
    Route::get('/article/detail','ArticleController@getDetail');
    //分类
    Route::get('/category/get_art_list','CategoryController@getArtList');

    Route::group(['middleware' => ['api.auth']], function(){
        Route::get('logout','LoginController@logout');
        //图片上传
        Route::post('/file/head_img','FileController@uploadHeadImg');
        //短信
        Route::post('/sms/update_pwd_code','SmsController@updatePwdCode');
        //用户
        Route::get('/user/get_user','UserController@getUser');
        Route::post('/user/update_pwd','UserController@updatePwd');
        Route::post('/user/update_user','UserController@updateUser');

    });
});
