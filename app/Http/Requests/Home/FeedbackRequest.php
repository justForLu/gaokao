<?php

namespace App\Http\Requests\Home;

use App\Http\Requests\Request;

class FeedbackRequest extends Request
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
            'name' => 'required|min:2|max:6',
            'content' => 'required|min:10|max:500'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'name.required' => '请输入姓名',
            'name.min' => '姓名最少2个字',
            'name.max' => '姓名最多6个字',
            'content.required' => '请输入内容',
            'content.min' => '内容最少10个字',
            'content.max' => '内容最多500字',
        ];
    }

}
