<?php
namespace App\Http\Controllers\Home;


class ImageController extends BaseController
{

    /**
     * 输出验证码
     */
	public function smsCode()
    {
        ob_clean();
        cookie('code',NULL);
        session('code',NULL);

        $code=$this->rand_str(4);
        cookie('code',$code);
        session('code',$code);

        $x_size=75;
        $y_size=30;
        $aimg = imagecreate($x_size,$y_size);
        $back = imagecolorallocate($aimg,255, 255, 255);
        $border = imagecolorallocate($aimg,204,53,53);
        imagefilledrectangle($aimg, 10, 10, $x_size+1, $y_size+1, $back);
        imagerectangle($aimg,100,100, $x_size, $y_size, $border);
        imageString($aimg,30,20,8, $code,$border);
        // 5. 干扰线（点）
        $num = 5;
        $width = 100;
        $height = 30;
        for($i = 0;$i < $num;$i++){
            imagearc($aimg, mt_rand(1, $width), mt_rand(10, $height), mt_rand(1, $width), mt_rand(10, $height), mt_rand(0, 10), mt_rand(0, 270), $this->deepColor($aimg));// 画椭圆弧
        }
        for($i = 0;$i < 50;$i++){
            imagesetpixel($aimg, mt_rand(10, $width), mt_rand(1, $height), $this->deepColor($aimg));// 画一个单一像素
        }
        header("Pragma:no-cache");
        header("Cache-control:no-cache");
        header("Content-type: image/png");
        imagepng($aimg,'test.png');
        imagedestroy($aimg);
    }

    /**
     * 生成随机码
     * @param $len
     * @return string
     */
    function rand_str($len){
        $chars = array(
             "1", "2","3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars)-1;
        shuffle($chars);
        $outStr='';
        for ($i=0;$i<$len;$i++){
            $outStr.=$chars[mt_rand(0,$charsLen)];
        }
        return $outStr;
    }

    /**
     * 图形验证码
     * @param int $width
     * @param int $height
     * @param int $num
     * @param int $type
     * @return bool|string
     */
    function verify($width = 100,$height = 40,$num = 5,$type = 3){
        // 1. 准备画布
        $image = imagecreatetruecolor($width, $height); // 设置验证码图片大小的函数
        // 2. 生成颜色（背景填充颜色，字体颜色）
        imagefilledrectangle($image, 0, 0, $width, $height, $this->lightColor($image));// 画一矩形并填充
        // 3. 你需要什么样的字符
        $string = '';
        switch($type){
            case 1:
                $str = '0123456789';
                $string = substr(str_shuffle($str), 0 ,$num);
                break;
            case 2:
                $arr = range('a','z');
                shuffle($arr);
                $tmp = array_slice($arr,0,5);
                $string = join('', $tmp);
                break;
            case 3:
                // 0-9 a-z A-Z
                $str = '0123456789abcdefghizklmnopqrstuvwxyzABCDEFGHIZKLMNOPQRSTUVWXYZ'; // 也可取出类似的0il
                $string = substr(str_shuffle($str),0,$num);
                break;
        }
        // 4. 开始写字
        $fontsize = 16; // 字大小
        for($i = 0;$i < $num;$i++){
            $x = floor($width / $num) * $i + 4;
            $y = mt_rand(10, $height - 20);
            imagechar($image, $fontsize, $x, $y, $string[$i], $this->deepColor($image));// 水平低画一个字符
        }
        // 5. 干扰线（点）
        for($i = 0;$i < $num;$i++){
            imagearc($image, mt_rand(10, $width), mt_rand(10, $height), mt_rand(10, $width), mt_rand(10, $height), mt_rand(0, 10), mt_rand(0, 270), $this->deepColor($image));// 画椭圆弧
        }
        for($i = 0;$i < 50;$i++){
            imagesetpixel($image, mt_rand(10, $width), mt_rand(10, $height), $this->deepColor($image));// 画一个单一像素
        }
        // 6. 指定输出的类型
        header('Content-type:image/png');
        // 7. 准备输出图片
        imagepng($image,'/home/test.png');
        // 8. 销毁
        imagedestroy($image); // 结束图形函数 销毁$image

        return $string;
    }

    /**
     * 浅色
     * @param $image
     * @return false|int
     */
    function lightColor($image){
        return imagecolorallocate($image, mt_rand(130, 255),mt_rand(130, 255), mt_rand(130, 255)); // 为一幅图像分配颜色
    }

    /**
     * 深色
     * @param $image
     * @return false|int
     */
    function deepColor($image){
        return imagecolorallocate($image, mt_rand(0, 120),mt_rand(0, 120), mt_rand(0, 120));
    }
}
