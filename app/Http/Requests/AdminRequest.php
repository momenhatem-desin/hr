<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'permission_roles_id' =>'required_if:usertype,1',
            'username' => 'required',
            'password' => 'required',
            'active' => 'required',
            'usertype' => 'required',
            'employees_code' => 'required_if:usertype,2',
            

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المستخدم كاملا مطلوب',
            'permission_roles_id.required_if' => 'دور صلاحية المستخدم مطلوب',
            'username.required' => 'اسم المستخدم للدخول به  مطلوب',
            'password.required' => ' كلمة المرور مطلوبة',
            'active.required' => '      حالة التفعيل مطلوبة',
            'usertype.required' => '     نوع المستخدم مطلوب',
            'employees_code.required_if' => '  كود الموظف مطلوب فى حاله انه موظف ',

        ];
    }
}
