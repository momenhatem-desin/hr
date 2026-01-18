<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployessUpdateRequest extends FormRequest
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
            'Qualifications_id'=>'required',
            'Qualifications_year'=>'required',
            'graduation_estimate'=>'required',
            'Graduation_specialization'=>'required',
            'brith_date'=>'required',
            'emp_national_idenity'=>'required',
            'emp_identityPlace'=>'required',
            'emp_end_identityIDate'=>'required',
            'emp_nationality_id'=>'required',
            'emp_lang_id'=>'required',
            'religion_id'=>'required',
            'staies_address'=>'required',
            'emp_home_tel'=>'required',
            'emp_work_tel'=>'required',
            'emp_Departments_code'=>'required',
            'emp_sal'=>'required',
            'is_active_for_Vaccation'=>'required',
            'emp_military_id'=>'required',
            'Does_have_fixed_allowances'=>'required',
            'does_has_ateendance'=>'required',
            'is_has_fixced_shift'=>'required',
            'shift_type_id'=>'required_if:is_has_fixced_shift,1',
            'daily_work_hour'=>'required_if:is_has_fixced_shift,0',
            'MotivationType'=>'required',
            'Motivation'=>'required_if:MotivationType,1',
            'isSocialnsurance'=>'required',
            'Socialnsurancecutmonthely'=>'required_if:isSocialnsurance,1',
            'SocialnsuranceNumber'=>'required_if:isSocialnsurance,1',
            'ismedicalinsurance'=>'required',
            'medicalinsurancecutmonthely'=>'required_if:ismedicalinsurance,1',
            'medicalinsuranceNumber'=>'required_if:ismedicalinsurance,1',
             'sal_cach_or_visa'=>'required',



            //'emp_military_date_from'=>'required_if:emp_military_id,1',


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
            'Qualifications_id.required'=>'المؤهل الدراسى مطلوب',
            'Qualifications_year.required'=>'سنة التخرج مطلوب',
            'graduation_estimate.required'=>'التقدير مطلوب',
            'Graduation_specialization.required'=>'التخصص مطلوب',
            'brith_date.required'=>'تاريخ الميلاد مطلوب',
            'emp_national_idenity.required'=>'رقم البطاقة مطلوب',
            'emp_identityPlace.required'=>'مركز اصدار البطاقة مطلوب',
            'emp_end_identityIDate.required'=>'تاريخ انتاء البطاقة مطلوب',
            'emp_nationality_id.required'=>'الجنسية مطلوبة',
            'emp_lang_id.required'=>'اللغة الاساسية مطلوبة',
            'religion_id.required'=>'الديانة مطلوبة',
            'staies_address.required'=>'عنوان الاقامة مطلوب',
            'emp_home_tel.required'=>'تليفون المنزل مطلوب',
            'emp_work_tel.required'=>'هاتف العمل مطلوب',
            'emp_Departments_code.required'=>'الادارة التابع لها الموظف مطلوب',
            'emp_sal.required'=>'راتب الموظف مطلوب',
            'is_active_for_Vaccation.required'=>'حقل الاجازات مطلوب',
            'emp_military_id.required'=>'حالة الخدمة العسكرية مطلوب',
            //'emp_military_date_from.required_if'=>'هذا الحقل مطلوب',
            'Does_have_fixed_allowances.required'=>'هذا الحقل مطلوب',
            'does_has_ateendance.required'=>'هذا الحقل مطلوب',
            'is_has_fixced_shift.required'=>'هذا الحقل مطلوب',
            'shift_type_id .required_if'=>'هذا الحقل مطلوب',
            'daily_work_hour.required_if'=>'هذا الحقل مطلوب',
            'MotivationType.required'=>'هذا الحقل مطلوب',
            'Motivation.required_if'=>'هذا الحقل مطلوب',
            'isSocialnsurance.required'=>'هذا الحقل مطلوب',
            'Socialnsurancecutmonthely.required_if'=>'هذا الحقل مطلوب',
            'SocialnsuranceNumber.required_if'=>'هذا الحقل مطلوب',
            'ismedicalinsurance.required'=>'هذا الحقل مطلوب',
            'medicalinsurancecutmonthely.required_if'=>'هذا الحقل مطلوب',
            'medicalinsuranceNumber.required_if'=>'هذا الحقل مطلوب',
             'sal_cach_or_visa.required'=>'هذا الحقل مطلوب',
             ];   
    }
}
