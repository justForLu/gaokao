<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class MajorLineRequest extends Request
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
            'major_id' => 'required',
            'province' => 'required',
            'year' => 'required',
            'science' => 'required',
            'batch' => 'required',
            'min_score' => 'required',
            'min_rank' => 'required',
            'recruit_num' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'school_id.required' => '请选择高校',
            'major_id.required' => '请选择专业',
            'province.required' => '请选择省份',
            'year.required' => '请选择年份',
            'science.required' => '请选择文理科',
            'batch.required' => '请选择批次',
            'min_score.required' => '请输入最低分',
            'min_rank.required' => '请输入最低分位次',
            'recruit_num.required' => '请输入招生人数',
        ];
    }

}
