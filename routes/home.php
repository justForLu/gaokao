<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/home/index.html');
});
Route::get('home', function () {
    return redirect('/home/index.html');
});

Route::group(['prefix' => 'home', 'namespace' => 'Home'], function (){
    //登录注册
    Route::get('/login', 'LoginController@index');
    Route::post('/login', 'LoginController@login');
    Route::get('/register', 'RegisterController@index');
    Route::post('/register', 'RegisterController@register');
    Route::get('/logout', 'LoginController@logout');
    //首页
    Route::get('/index.html', 'IndexController@index');
    //查高校
    Route::any('/school/index.html', 'SchoolController@index');
    Route::get('/school/detail/{id}.html', 'SchoolController@detail');
    //查分数线
    Route::get('/score/index.html', 'ScoreController@index');
    Route::get('/score/get_list', 'ScoreController@getList');
    //专业解读
    Route::get('/article/index.html', 'ArticleController@index');
    Route::get('/article/detail/{id}.html', 'ArticleController@detail');
    //关于中夏教育
    Route::get('/about/index.html', 'AboutController@index');
    //反馈
    Route::post('/feedback','FeedbackController@feedback');

    Route::group(['middleware' => ['home.auth']], function(){
        //个人中心
        Route::get('/user/message.html', 'UsersController@message');
        Route::get('/user/cash_out.html', 'UsersController@cash_out');
        Route::get('/user/info.html', 'UsersController@info');
        Route::post('/user/sub_info', 'UsersController@sub_info');
        Route::get('/user/collect_recruit.html', 'UsersController@collect_recruit');
        Route::get('/user/collect_shop.html', 'UsersController@collect_shop');
        Route::get('/user/portrait.html', 'UsersController@portrait');
        Route::post('/user/sub_portrait', 'UsersController@sub_portrait');
        Route::get('/user/account.html', 'UsersController@account');
        Route::post('/user/sub_account_pwd', 'UsersController@sub_account_pwd');
        Route::post('/user/sub_account_mobile', 'UsersController@sub_account_mobile');
        Route::post('/join/join_in','JoinController@join_in');
        Route::post('/collect/collect','CollectController@collect');
    });
});



