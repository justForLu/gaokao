<?php
use App\Models\Admin\File as FileModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;

if (!function_exists('get_date')) {

    /**
     * 获取当前时间
     * @return bool|string
     */
    function get_date(){
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('get_action_name')) {

    /**
     * 获取当前请求的控制器和方法名
     * @return bool|string
     */
    function get_action_name(){
        $action = strtolower(Route::current()->getActionName());
        list($class, $method) = explode('@', $action);
        $class = substr(strrchr($class,'\\'),1,-10);
        return array('controller' => $class, 'method' => $method);
    }
}

if (!function_exists('make_thumb_images')) {

    /**
     * 生成指定尺寸的缩略图
     * @param $id
     * @param $width
     * @param $height
     * @return string
     */
    function make_thumb_images($id,$width,$height){
        if(empty($id))
            return '';

        // 判断缩略图是否存在
        $path = "upload/admin/thumb/$id/";
        $thumb_name ="thumb_{$id}_{$width}_{$height}";

        // 已经生成过这个比例的图片就直接返回了
        $extArr = array('.jpg','.jpeg','.gif','.png');

        foreach($extArr as $ext){
            if(file_exists($path.$thumb_name.$ext))  return '/'.$path.$thumb_name.$ext;
        }

        $original_img = FileModel::where('id',$id)->first();
        $original_img_path = substr($original_img->path,1);
        if(empty($original_img_path)) return '';

        // 生成缩略图
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        $img = Image::make($original_img_path)->resize($width,$height);

        $original_img_ext = '.'.File::extension($original_img_path);
        $img->save($path.$thumb_name.$original_img_ext);
        return '/'.$path.$thumb_name.$original_img_ext;
    }
}

if (!function_exists('is_wx_browser')) {

    /**
     * 微信浏览器判断
     * @return bool|string
     */
    function is_wx_browser(){
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'micromessenger') !== false) {
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('get_client_ip')){
    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}

if(!function_exists('generate_code')){
    /**
     * 生成短信验证码
     * @param int $length
     * @return int
     */
    function generate_code($length = 6) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }
}

if(!function_exists('is_mobile')){
    function dstrpos($string, $arr, $returnvalue = false) {
        if(empty($string)) return false;
        foreach((array)$arr as $v) {
            if(strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }
    /**
     * discuz中检查手机端还是PC端
     * @return bool|string
     */
    function is_mobile() {
        global $_G;
        $mobile = array();
        static $touchbrowser_list =array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
            'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
            'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
            'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
            'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
            'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
            'benq', 'haier', '^lct', '320x320', '240x320', '176x220', 'windows phone');
        static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom',
            'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh',
            'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');

        static $pad_list = array('ipad');

        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

        if(dstrpos($useragent, $pad_list)) {
            return false;
        }
        if(($v = dstrpos($useragent, $touchbrowser_list, true))){
            $_G['mobile'] = $v;
            return '2';
        }
        if(($v = dstrpos($useragent, $wmlbrowser_list))) {
            $_G['mobile'] = $v;
            return '3'; //wml版
        }
        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if(dstrpos($useragent, $brower)) return false;

        $_G['mobile'] = 'unknown';
        if(isset($_G['mobiletpl'][$_GET['mobile']])) {
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('set_index'))
{
    function set_index($array = [], $key = '', $key_val = '')
    {
        $result = [];
        if (! $array || ! is_array($array) || ! $key) {
            return $result;
        }
        foreach ($array as $val)
        {
            if(isset($val[$key])){
                $result[$val[$key]] = $key_val ? $val[$key_val] : $val;
            }
        }
        return $result;
    }
}

if (! function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @param  string|null  $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth($guard = null)
    {
        if (is_null($guard)) {
            return app(AuthFactory::class);
        }

        return app(AuthFactory::class)->guard($guard);
    }
}

/**
 * 验证是否是手机号
 */
if(!function_exists('check_mobile')){
    function check_mobile($str = ''){
        if(preg_match("/^1[3456789]{1}\d{9}$/",$str)){
            return true;
        }
        return false;
    }
}

/**
 * 验证是否是邮箱
 */
if(!function_exists('check_email')) {
    function check_email($str = ''){
        if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix",$str)){
            return true;
        }
        return false;
    }
}

/**
 * 获取HTTP协议还是HTTPS协议
 */
if(!function_exists('get_http_type')) {
    function get_http_type()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type;
    }
}

/**
 * 格式化金额
 */
if(!function_exists('format_money')) {
    function format_money($money = 0){
        return sprintf("%01.2f", $money);
    }
}

/**
 * 检查是否含有汉字
 */
if(!function_exists('check_chinese')) {
    function check_chinese($str = '')
    {
        if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $str)) {
            return true;    //含有汉字
        } else {
            return false;   //不包含汉字
        }
    }
}

/**
 * 获取昨日日期
 */
if(!function_exists('get_yesterday')) {
    function get_yesterday()
    {
        return date('Y-m-d', strtotime('-1 Day'));
    }
}

/**
 * 获取本周起止日期
 */
if(!function_exists('get_week')) {
    function get_week()
    {
        $week_begin_date = date('Y-m-d',(time()-((date('w',time())==0?7:date('w',time()))-1)*24*3600));
        $week_end_date = date('Y-m-d', time());

        return ['begin_time' => $week_begin_date,'end_time' => $week_end_date];
    }
}

/**
 * 获取本月起止日期
 */
if(!function_exists('get_month')) {
    function get_month()
    {
        $month_begin_date = date('Y-m-' . '01');
        $month_end_date = date('Y-m-d', time());

        return ['begin_time' => $month_begin_date,'end_time' => $month_end_date];
    }
}

/**
 * 获取上月起止日期
 */
if(!function_exists('get_last_month')) {
    function get_last_month()
    {
        $last_month_begin = date('Y-m-d', strtotime(date('Y-m-' . '01') . '-1 MONTH'));
        $last_month_end = date('Y-m-d', strtotime(date('Y-m-' . '01') . '-1 Day'));

        return ['begin_time' => $last_month_begin,'end_time' => $last_month_end];
    }
}

/**
 * 返回昨日、今日、本周（截至今日）、本月（截至今日）、上月时间段
 */
function fn_getDates()
{
    // 昨日
    $last_day = date('Y-m-d', strtotime('-1 Day'));

    // 今日
    $today = date('Y-m-d', time());

    // 本周
    $week_begin_date = date('Y-m-d',(time()-((date('w',time())==0?7:date('w',time()))-1)*24*3600));
    $week_end_date = date('Y-m-d', time());

    // 本月
    $month_begin_date = date('Y-m-' . '01');
    $month_end_date = date('Y-m-d', time());

    // 上月
    $last_month_begin = date('Y-m-d', strtotime(date('Y-m-' . '01') . '-1 MONTH'));
    $last_month_end = date('Y-m-d', strtotime(date('Y-m-' . '01') . '-1 Day'));

    $date = [
        [
            'time_name' => '昨日',
            'begin_time' => $last_day,
            'end_time' => $last_day
        ],
        [
            'time_name' => '今日',
            'begin_time' => $today,
            'end_time' => $today
        ],
        [
            'time_name' => '本周',
            'begin_time' => $week_begin_date,
            'end_time' => $week_end_date
        ],
        [
            'time_name' => '本月',
            'begin_time' => $month_begin_date,
            'end_time' => $month_end_date
        ],
        [
            'time_name' => '上月',
            'begin_time' => $last_month_begin,
            'end_time' => $last_month_end
        ]
    ];

    return $date;
}



