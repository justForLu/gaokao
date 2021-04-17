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
    return redirect('admin');
});
Route::get('admin', function () {
    return redirect('/admin/index');
});
Route::get('/getImg/{id}/{w?}/{h?}', function ($id,$w,$h) {
    return redirect()->route('getImg', ['id'=>$id,'w'=>$w,'h'=>$h]);
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function (){
    //不经过中间件的路由
    Route::get('/homepage','IndexController@homepage');
    Route::get('/login', 'LoginController@index');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');
    Route::get('/getImg/{id}/{w?}/{h?}', ['as' => 'getImg', 'uses' => 'FileController@getImg']);
    Route::post('/file/uploadPic','FileController@uploadPic');
    Route::post('/file/editUploadPic','FileController@editUploadPic');
    Route::post('/file/uploadFile','FileController@uploadFile');
    Route::get('/get_city_list', 'CityController@get_city_list');
    Route::any('/manager/my_info', 'ManagerController@myInfo');
    Route::any('/manager/my_pwd', 'ManagerController@myPwd');

    Route::group(['middleware' => ['admin.auth','admin.log','admin.check']], function(){
        Route::get('/index', 'IndexController@index');

        /**
         * 后台管理
         */
        // 管理员管理
        Route::get('/manager/get_list', 'ManagerController@getList');
        Route::resource('/manager', 'ManagerController');
        //角色管理
        Route::get('/role/get_list', 'RoleController@getList');
        Route::resource('/role', 'RoleController');
        Route::match(['get', 'post'],'/role/authority/{id?}', 'RoleController@authority');
        //日志列表
        Route::get('/log', 'LogController@index');
        Route::get('/log/get_list', 'LogController@getList');
        //反馈列表
        Route::post('/feedback/change_value', 'FeedbackController@changeValue');
        Route::get('/feedback/get_list', 'FeedbackController@getList');
        Route::resource('/feedback', 'FeedbackController');

        /**
         * 系统设置
         */
        //城市列表
        Route::post('/city/change_value', 'CityController@changeValue');
        Route::get('/city/get_list', 'CityController@getList');
        Route::resource('/city', 'CityController');
        //配置管理
        Route::post('/config/change_value', 'ConfigController@changeValue');
        Route::get('/config/get_list', 'ConfigController@getList');
        Route::resource('/config', 'ConfigController');
        //轮播图列表
        Route::post('/banner/change_value', 'BannerController@changeValue');
        Route::get('/banner/get_list', 'BannerController@getList');
        Route::resource('/banner', 'BannerController');
        //分类管理
        Route::post('/category/change_value', 'CategoryController@changeValue');
        Route::get('/category/get_list', 'CategoryController@getList');
        Route::resource('/category', 'CategoryController');

        /**
         * 分数线管理
         */
        //分数线列表
        Route::post('/score/change_value', 'ScoreController@changeValue');
        Route::get('/score/get_list', 'ScoreController@getList');
        Route::resource('/score', 'ScoreController');

        /**
         * 文章管理
         */
        //文章列表
        Route::post('/article/change_value', 'ArticleController@changeValue');
        Route::get('/article/get_list', 'ArticleController@getList');
        Route::resource('/article', 'ArticleController');

        /**
         * 高校管理
         */
        //高校列表
        Route::post('/school/change_value', 'SchoolController@changeValue');
        Route::get('/school/get_list', 'SchoolController@getList');
        Route::resource('/school', 'SchoolController');

        /**
         * 用户管理
         */
        //用户列表
        Route::get('/user/get_list', 'UserController@getList');
        Route::post('/user/change_value', 'UserController@changeValue');
        Route::resource('/user', 'UserController');

    });
});
