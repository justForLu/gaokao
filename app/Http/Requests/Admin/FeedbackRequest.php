<?php

namespace App\Http\Requests\Admin;

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
            'key_word' => 'required',
            'content' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'key_word.required' => '请输入关键词',
            'content.required' => '请输入自动回复内容'
        ];
    }

}
