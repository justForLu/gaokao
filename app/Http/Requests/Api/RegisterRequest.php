<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

class RegisterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile' => 'required',
            'password' => 'required|min:6|max:16',
            're_password' => 'required',
            'invite_code'   => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'mobile.required' => '请输入手机号',
            'password.required' => '请输入密码',
            'password.min' => '密码长度最低6个字符',
            'password.max' => '密码长度最多16个字符',
            're_password.required' => '请再次输入密码',
            'invite_code.required' => '请输入邀请码'
        ];
    }

}
