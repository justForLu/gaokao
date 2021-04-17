<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SchoolRequest extends Request
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
            'province' => 'required',
            'city' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'name.required' => '请输入高校名称',
            'province.required' => '请选择省份',
            'city.required' => '请选择城市',
        ];
    }

}
