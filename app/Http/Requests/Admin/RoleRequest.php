<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class RoleRequest extends Request
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
            'name' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return  [
            'name.required' => '请输入角色名称'
        ];
    }

}
