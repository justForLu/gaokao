<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UserRequest extends Request
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
            'username' => 'required',
            'password' => 'required',
            'mobile' => 'required',
            'real_name' => 'required',
            'nickname' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'username.required' => '请输入用户名',
            'password.required' => '请输入密码',
            'mobile.required' => '请输入手机号',
            'real_name.required' => '请输入真实姓名',
            'nickname.required' => '请输入昵称',
        ];
    }

}
