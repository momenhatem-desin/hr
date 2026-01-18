<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserprofileRequest extends FormRequest
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
            'username' => 'required',
            'email' => 'email',
            'password' => 'required_if:checkForupdatePassword,1'
    
        ];
    }


     public function messages()
    {
        return [
            'username.required' => 'اسم المستخدم  مطلوب',
            'email.required' => '   الاميل مطلوب',
            'password.required_if' => 'الباسورد مطلوب'

        ];
    }
}
