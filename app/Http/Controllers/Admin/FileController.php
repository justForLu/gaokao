<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class FileController extends BaseController
{


    /*图片上传*/
    public function uploadPic(Request $request){
        $filename = $_FILES['file']['name'];
        if ($filename) {
            //文件类型的点最后一次出现的位置
            $file_index = mb_strrpos($filename, '.');
            //验证是不是图片
            $is_img = getimagesize($_FILES["file"]["tmp_name"]);
            if(!$is_img){
                return $this->ajaxError('不是图片');    //根据自己需要返回
            }

            //验证类型
            $image_type = ['image/png','image/jpg','image/jpeg'];

            if(!in_array($_FILES['file']['type'], $image_type)){
                return $this->ajaxError('文件类型只能为png、JPG格式图片');    //根据自己需要返回数据
            }
            //验证后缀
            $postfix = ['.png','.jpg','.jpeg'];
            $file_postfix = strtolower(mb_substr($filename, $file_index));

            if(!in_array($file_postfix, $postfix)){
                return $this->ajaxError('文件后缀只能是png，jpg，jpeg');    //根据自己需要返回数据
            }

            //文件大小限制800KB
            if($_FILES['file']['size'] > 800*1024){
                return $this->ajaxError('图片过大，限制800KB');    //根据自己需要返回数据
            }
            //重命名文件。
            $filename = date('YmdHis',time()) . mt_rand(100000, 999999). $file_postfix;
            //新建文件夹（如果文件夹不存在）
            $rootPath1 = Config::get('admin.document_root');
            $rootPath2 = Config::get('admin.uploadImg','');
            $savePath = '/'.date('Y-m').'/'.date('d');
            $folder = $rootPath1.$rootPath2.$savePath;
            if (!file_exists($folder)){
                mkdir($folder,0777,true);
            }
            //移动文件
            $result = move_uploaded_file($_FILES["file"]["tmp_name"],
                $folder . "/" . $filename);
            if($result){
                $path = $rootPath2.$savePath . "/"  . $filename;    //路径

                $img_url = get_http_type().$_SERVER['HTTP_HOST'].$path; //图片地址

                return $this->ajaxSuccess(['path' => $path, 'img_url' => $img_url],'上传成功');
            }else{
                return $this->ajaxError('上传失败');
            }

        }else{
            return $this->ajaxError('未找到上传图片');
        }

    }

    /**
     * 富文本图片上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUploadPic(Request $request){
        $filename = $_FILES['image']['name'];
        if ($filename) {
            //文件类型的点最后一次出现的位置
            $file_index = mb_strrpos($filename, '.');
            //验证是不是图片
            $is_img = getimagesize($_FILES["image"]["tmp_name"]);
            if(!$is_img){
                return $this->ajaxError('不是图片');    //根据自己需要返回
            }

            //验证类型
            $image_type = ['image/png','image/jpg','image/jpeg'];

            if(!in_array($_FILES['image']['type'], $image_type)){
                return $this->ajaxError('文件类型只能为png、JPG格式图片');    //根据自己需要返回数据
            }
            //验证后缀
            $postfix = ['.png','.jpg','.jpeg'];
            $file_postfix = strtolower(mb_substr($filename, $file_index));

            if(!in_array($file_postfix, $postfix)){
                return $this->ajaxError('文件后缀只能是png，jpg，jpeg');    //根据自己需要返回数据
            }

            //文件大小限制800KB
            if($_FILES['image']['size'] > 800*1024){
                return $this->ajaxError('图片过大，限制800KB');    //根据自己需要返回数据
            }
            //重命名文件。
            $filename = date('YmdHis',time()) . mt_rand(100000, 999999). $file_postfix;
            //新建文件夹（如果文件夹不存在）
            $rootPath1 = Config::get('admin.document_root');
            $rootPath2 = Config::get('admin.uploadImg','');
            $savePath = '/'.date('Y-m').'/'.date('d');
            $folder = $rootPath1.$rootPath2.$savePath;
            if (!file_exists($folder)){
                mkdir($folder,0777,true);
            }
            //移动文件
            $result = move_uploaded_file($_FILES["image"]["tmp_name"],
                $folder . "/" . $filename);
            if($result){
                $path = $rootPath2.$savePath . "/"  . $filename;    //路径

                $img_url = get_http_type().$_SERVER['HTTP_HOST'].$path; //图片地址

                return $this->ajaxSuccess(['img_url' => [$img_url]],'上传成功');
            }else{
                return $this->ajaxError('上传失败');
            }

        }else{
            return $this->ajaxError('未找到上传图片');
        }

    }

    /*获得图片路径*/
    static function getFilePath($pics){
        $pics = explode(',',$pics);
        $data = array();
        foreach($pics as $picId){
            if(!empty($picId))
                $data[$picId] = File::where('id',$picId)->value('path');
        }
        return $data;
    }


    /**
     * 获取图片
     * @param Request $request
     * @return string
     */
    public function getImg(Request $request){
        $result = make_thumb_images($request->id,$request->w,$request->h);

        return redirect('http://'.$_SERVER['HTTP_HOST'].$result);
    }

    /*  上传文件  */
    public function uploadFile(Request $request){
//        $file = $request->file('Filedata'); // 不同环境可能获取方式有点不同，可以下打印观察一下 dd(Input());
//        if($file->isValid())
//        {
//            // 上传目录。 public目录下 uploads/thumb 文件夹
//            $dir = 'upload/admin/videos/';
//
//            // 文件名。格式：时间戳 + 6位随机数 + 后缀名
//            $filename = time() . mt_rand(100000, 999999) . '.' . $file ->getClientOriginalExtension();
//
//            $file->move($dir, $filename);
//            $path = $dir . $filename;
//
//            return response()->json($path);
//        }
        $filename = $_FILES['file']['name'];
        if ($filename) {
            //重命名文件
            $file = explode('.',$filename);
            $filename = date('YmdHis',time()) . mt_rand(100000, 999999) . '.' . $file[count($file) - 1];
            //新建文件夹（如果文件夹不存在）
            $folder = "upload/admin/videos/" . date('Y-m-d',time());
            if (!file_exists($folder)){
                mkdir($folder,0777,true);
            }
            move_uploaded_file($_FILES["file"]["tmp_name"],
                "upload/admin/videos/" . date('Y-m-d',time()) . "/" . $filename);

            $path = "/upload/admin/videos/" . date('Y-m-d',time()) . "/"  . $filename;
            return response()->json($path);
        }

    }
}
