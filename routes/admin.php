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
    Route::get('/index/user_spread','IndexController@userSpreadSta');
    Route::post('/file/uploadPic','FileController@uploadPic');
    Route::post('/file/editUploadPic','FileController@editUploadPic');
    Route::post('/file/uploadFile','FileController@uploadFile');

    Route::group(['middleware' => ['admin.auth','admin.log','admin.check']], function(){
        Route::get('/index', 'IndexController@index');

        /**
         * 后台管理
         */
        // 管理员管理
        Route::any('/manager/my_info', 'ManagerController@myInfo');
        Route::any('/manager/my_pwd', 'ManagerController@myPwd');
        Route::get('/manager/get_list', 'ManagerController@getList');
        Route::resource('/manager', 'ManagerController');
        //角色管理
        Route::get('/role/get_list', 'RoleController@getList');
        Route::resource('/role', 'RoleController');
        Route::match(['get', 'post'],'/role/authority/{id?}', 'RoleController@authority');
        //菜单管理
        Route::get('/menu/get_list', 'MenuController@getList');
        Route::resource('/menu', 'MenuController');
        //权限管理
        Route::get('/permission/get_list', 'PermissionController@getList');
        Route::resource('/permission', 'PermissionController');
        //日志列表
        Route::get('/log', 'LogController@index');
        Route::get('/log/get_list', 'LogController@getList');

        /**
         * 系统设置
         */
        //城市列表
        Route::post('/city/change_value', 'CityController@changeValue');
        Route::get('/city/get_list', 'CityController@getList');
        Route::get('/get_city_list', 'CityController@get_city_list');
        Route::resource('/city', 'CityController');
        //配置管理
        Route::post('/config/change_value', 'ConfigController@changeValue');
        Route::get('/config/get_list', 'ConfigController@getList');
        Route::resource('/config', 'ConfigController');
        //轮播图列表
        Route::post('/banner/change_value', 'BannerController@changeValue');
        Route::get('/banner/get_list', 'BannerController@getList');
        Route::resource('/banner', 'BannerController');
        //支付方式
        Route::post('/payment/change_value', 'PaymentController@changeValue');
        Route::get('/payment/get_list', 'PaymentController@getList');
        Route::resource('/payment', 'PaymentController');

        /**
         * 抢单管理
         */
        //抢单列表
        Route::post('/commodity/change_value', 'CommodityController@changeValue');
        Route::get('/commodity/get_list', 'CommodityController@getList');
        Route::resource('/commodity', 'CommodityController');
        //场次管理
        Route::post('/slot/change_value', 'SlotController@changeValue');
        Route::get('/slot/get_list', 'SlotController@getList');
        Route::resource('/slot', 'SlotController');
        //抢单配置
        Route::post('/deploy/change_value', 'DeployController@changeValue');
        Route::get('/deploy/get_list', 'DeployController@getList');
        Route::resource('/deploy', 'DeployController');

        /**
         * 商城管理
         */
        //商品分类
        Route::post('/category/change_value', 'CategoryController@changeValue');
        Route::get('/category/get_list', 'CategoryController@getList');
        Route::resource('/category', 'CategoryController');
        //商品列表
        Route::post('/goods/change_value', 'GoodsController@changeValue');
        Route::get('/goods/get_list', 'GoodsController@getList');
        Route::resource('/goods', 'GoodsController');

        /**
         * 订单管理
         */
        //订单列表
        Route::get('/order/get_list', 'OrderController@getList');
        Route::resource('/order', 'OrderController');

        /**
         * 用户管理
         */
        //用户列表
        Route::get('/user/get_list', 'UserController@getList');
        Route::any('/user/recharge/{id}', 'UserController@Recharge');
        Route::post('/user/change_value', 'UserController@changeValue');
        Route::resource('/user', 'UserController');
        //玉豆日志
        Route::get('/bean_log/get_list', 'BeanLogController@getList');
        Route::resource('/bean_log', 'BeanLogController');
        //会员等级
        Route::post('/grade/change_value', 'GradeController@changeValue');
        Route::get('/grade/get_list', 'GradeController@getList');
        Route::resource('/grade', 'GradeController');



        //无用路由
        Route::get('/feedback/get_list', 'FeedbackController@getList');
        Route::post('/feedback/change_value', 'FeedbackController@changeValue');
        Route::resource('/feedback', 'FeedbackController');
        Route::post('/car_category/change_value', 'CarCategoryController@changeValue');
        Route::get('/car_category/get_list', 'CarCategoryController@getList');
        Route::get('/car_category/get_by_car_type','CarCategoryController@getListByCarType');
        Route::resource('/car_category', 'CarCategoryController');
        Route::get('/charges/get_list', 'ChargesController@getList');
        Route::resource('/charges', 'ChargesController');
        Route::post('/device/change_value', 'DeviceController@changeValue');
        Route::get('/device/get_list', 'DeviceController@getList');
        Route::get('/device/car/{id}/{car_id}/{apply_id}', 'DeviceController@car');
        Route::get('/device/show_car/{car_id}', 'DeviceController@showCar');
        Route::post('/device/bind', 'DeviceController@bind');
        Route::post('/device/relieve', 'DeviceController@relieve');
        Route::post('/device/remove', 'DeviceController@remove');
        Route::resource('/device', 'DeviceController');
        Route::get('/device_fee/get_list', 'DeviceFeeController@getList');
        Route::get('/device_fee', 'DeviceFeeController@index');
        Route::get('/device_fee_report/get_list', 'DeviceFeeReportController@getList');
        Route::get('/device_fee_report', 'DeviceFeeReportController@index');
        Route::get('/device_log/get_list', 'DeviceLogController@getList');
        Route::get('/device_log', 'DeviceLogController@index');
        Route::get('/device_user_log/get_list', 'DeviceUserLogController@getList');
        Route::get('/device_user_log', 'DeviceUserLogController@index');
        Route::get('/flow_report/get_list', 'FlowReportController@getList');
        Route::get('/flow_report', 'FlowReportController@index');
        Route::post('/device_goods/change_value', 'DeviceGoodsController@changeValue');
        Route::get('/device_goods/get_list', 'DeviceGoodsController@getList');
        Route::resource('/device_goods', 'DeviceGoodsController');
        Route::get('/device_order/get_list', 'DeviceOrderController@getList');
        Route::resource('/device_order', 'DeviceOrderController');
        Route::get('/money_warning', 'MoneyWarningController@index');
        Route::get('/money_warning/get_list', 'MoneyWarningController@getList');
        Route::post('/money_warning/batch_del', 'MoneyWarningController@batchDel');
        Route::post('/money_warning/delete/{id}', 'MoneyWarningController@destroy');
        Route::post('/methanol/change_value', 'MethanolController@changeValue');
        Route::get('/methanol/get_list', 'MethanolController@getList');
        Route::resource('/methanol', 'MethanolController');
        Route::get('/oil/get_list', 'OilController@getList');
        Route::resource('/oil', 'OilController');
        Route::post('/article/change_value', 'ArticleController@changeValue');
        Route::get('/article/get_list', 'ArticleController@getList');
        Route::resource('/article', 'ArticleController');
        Route::get('/user_role/get_list', 'UserRoleController@getList');
        Route::get('/user_role', 'UserRoleController@index');
        Route::post('/user_role/change_value', 'UserRoleController@changeValue');
        Route::get('/car/get_list', 'CarController@getList');
        Route::resource('/car', 'CarController');
        Route::get('/cash/get_list', 'CashController@getList');
        Route::resource('/cash', 'CashController');
        Route::get('/apply/get_list', 'ApplyController@getList');
        Route::get('/apply', 'ApplyController@index');
        Route::get('/apply/edit/{id}', 'ApplyController@edit');
        Route::post('/apply/update', 'ApplyController@update');
        Route::get('/apply/show/{id}', 'ApplyController@show');
        Route::get('/region_agent/get_list', 'RegionAgentController@getList');
        Route::get('/region_agent', 'RegionAgentController@index');
        Route::get('/region_agent/check/{id}', 'RegionAgentController@check');
        Route::post('/region_agent/check_agent', 'RegionAgentController@checkAgent');
        Route::get('/spread/get_list', 'SpreadController@getList');
        Route::get('/spread', 'SpreadController@index');
        Route::get('/spread/edit/{id}', 'SpreadController@edit');
        Route::post('/spread/check', 'SpreadController@check');
        Route::post('/spread/revoke', 'SpreadController@revoke');
    });
});
