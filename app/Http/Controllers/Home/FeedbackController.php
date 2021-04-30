<?php
namespace App\Http\Controllers\Home;


use App\Http\Requests\Home\FeedbackRequest;
use App\Models\Common\Feedback;

class FeedbackController extends BaseController
{


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 反馈
     * @param FeedbackRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function feedback(FeedbackRequest $request)
    {
        $params = $request->all();
        $mobile = $params['mobile'] ?? '';
        $email = $params['email'] ?? '';
        if(empty($mobile) && empty($email)){
            return response()->json(['status' => 'fail','code' => 300,'msg' => '手机号和邮箱至少填一个']);
        }
        if(!empty($mobile) && !check_mobile($mobile)){
            return response()->json(['status' => 'fail','code' => 300,'msg' => '手机号格式不正确']);
        }
        if(!empty($email) && !check_email($email)){
            return response()->json(['status' => 'fail','code' => 300,'msg' => '邮箱格式不正确']);
        }

        $data = [
            'name' => $params['name'] ?? '',
            'mobile' => $mobile,
            'email' => $email,
            'content' => $params['content'],
            'create_time' => time()
        ];
        //检查今天提交了多少次，限制10次
        $count = 0;
        if($mobile){
            $count = Feedback::where('mobile',$mobile)->count();
        }elseif ($email){
            $count = Feedback::where('email',$email)->count();
        }
        if($count >= 10){
            return response()->json(['status' => 'fail','code' => 300,'msg' => '您已提交多次，请勿再重复提交']);
        }

        $result = Feedback::insert($data);
        if($result){
            return response()->json(['status' => 'fail','code' => 200,'msg' => '提交成功，请耐心等待','referrer' => 'home/about/index.html']);
        }

        return response()->json(['status' => 'fail','code' => 300,'msg' => '提交失败，请稍后重试']);
    }

}
