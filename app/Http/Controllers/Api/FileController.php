<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class FileController extends BaseController
{

    public function __construct(\Illuminate\Http\Request $request)
    {
        parent::__construct($request);

    }

    /**
     * 上传头像
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadHeadImg(Request $request)
    {
        $file = $_FILES['file'];
        $savePath = Config::get('api.uploadHeadImg').'/'.date('Y-m').'/'.date('d');
        $filename = date('YmdHis',time()) . mt_rand(100000, 999999);
        $file_size = 500;

        $res = $this->uploadPic($file,$savePath,$filename,$file_size);

        if($res && $res['code'] == 200){
            return $this->returnSuccess($res['data'],$res['msg']);
        }
        return $this->returnError($res['msg']);
    }

    /**
     * 图片上传方法
     * @param null $file    文件
     * @param string $savePath
     * @param string $filename
     * @param int $file_size    图片文件大小，单位：KB
     * @return mixed
     */
    public function uploadPic($file = null,$savePath = '',$filename = 'default',$file_size = 200)
    {
        if ($file) {
            //文件类型的点最后一次出现的位置
            $file_index = mb_strrpos($file['name'], '.');
            //验证是不是图片
            $is_img = getimagesize($file["tmp_name"]);
            if(!$is_img){
                return ['msg' => '不是图片文件','code' => 300];    //根据自己需要返回
            }
            //验证类型
            $image_type = ['image/png','image/jpg','image/jpeg'];

            if(!in_array($file['type'], $image_type)){
                return ['msg' => '文件类型只能为png、JPG格式图片','code' => 300];    //根据自己需要返回数据
            }
            //验证后缀
            $postfix = ['.png','.jpg','.jpeg'];
            $file_postfix = strtolower(mb_substr($file['name'], $file_index));
            if(!in_array($file_postfix, $postfix)){
                return ['msg' => '文件后缀只能是png，jpg，jpeg','code' => 300];    //根据自己需要返回数据
            }
            //文件大小限制默认200KB
            if($file['size'] > $file_size*1024){
                return ['msg' => '图片过大，限制'.$file_size.'KB','code' => 300];  //根据自己需要返回数据
            }
            //重命名文件。
            $filename = $filename. $file_postfix;
            //新建文件夹（如果文件夹不存在）
            $rootPath1 = Config::get('api.document_root');    //系统目录
            $folder = $rootPath1.$savePath;
            if (!file_exists($folder)){
                mkdir($folder,0777,true);
            }
            //移动文件
            $result = move_uploaded_file($file["tmp_name"],
                $folder . "/" . $filename);
            if($result){
                $path = $savePath . "/"  . $filename;    //路径

                $img_url = get_http_type().$_SERVER['HTTP_HOST'].$path; //图片地址
                $data = ['path' => $path, 'img_url' => $img_url];
                return ['data' => $data, 'code' => 200, 'msg' => '上传成功'];
            }else{
                return ['msg' => '上传失败','code' => 300];
            }
        }else{
            return ['msg' => '未找到图片','code' => 300];
        }
    }

}




