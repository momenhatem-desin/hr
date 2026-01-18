<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'emp_name'=>'required',
            'emp_gender'=>'required',
            'emp_home_tel'=>'required',
            'branch_id'=>'required',
            'emp_start_date'=>'required',
            'Functiona_status'=>'required',
            'emp_jobs_id'=>'required',

        ];
    }
    public function messages()
    {
        return [
            'emp_name.required'=>'اسم الموظف مطلوب',
            'emp_gender.required'=>'نوع الموظف مطلوب',
            'emp_home_tel.required'=>'رقم التليفون  مطلوب',
            'branch_id.required'=>'  الفرع مطلوب',
            'emp_start_date.required'=>' تاريخ التعين مطلوب  ',
            'Functiona_status.required'=>'حالة تفعيل الوظيفه مطلوب',
            'emp_jobs_id.required'=>'نوع الوظيفة مطلوب',
             ];   
    }
}
