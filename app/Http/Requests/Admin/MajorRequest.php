<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class MajorRequest extends Request
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
            'school_id' => 'required',
            'category_id' => 'required',
            'name' => 'required',
            'edu_system' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'school_id.required' => '请选择高校',
            'category_id.required' => '请选择分类',
            'name.required' => '请输入专业名称',
            'edu_system.required' => '请输入学制',
        ];
    }

}
