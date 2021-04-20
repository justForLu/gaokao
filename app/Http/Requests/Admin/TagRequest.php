<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class TagRequest extends Request
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
            'name' => 'required',
            'shorter' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'name.required' => '请输入标签名称',
            'shorter.required' => '请输入标签简称',
        ];
    }

}
