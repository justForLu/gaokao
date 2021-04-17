<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ScoreRequest extends Request
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
            'province' => 'required',
            'year' => 'required',
            'yiben_li' => 'required',
            'erben_li' => 'required',
            'sanben_li' => 'required',
            'dazhuan_li' => 'required',
            'yiben_wen' => 'required',
            'erben_wen' => 'required',
            'sanben_wen' => 'required',
            'dazhuan_wen' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'province.required' => '请选择省份',
            'year.required' => '请选择年份',
            'yiben_li.required' => '请输入理科一本线',
            'erben_li.required' => '请输入理科二本线',
            'sanben_li.required' => '请输入理科三本线',
            'dazhuan_li.required' => '请输入理科大专线',
            'yiben_wen.required' => '请输入文科一本线',
            'erben_wen.required' => '请输入文科二本线',
            'sanben_wen.required' => '请输入文科三本线',
            'dazhuan_wen.required' => '请输入文科大专线',
        ];
    }

}
