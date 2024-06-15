<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TelVerificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|size:6',
            'token' => 'required|string|uuid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'code.size' => 'کد صحیح نیست.',
            'code.required' => 'کد ارسال شده به شماره تلفن تان را وارد کنید.',
            'token.required' => 'لطفا توکن ارسال شده را وارد کنید.',
        ];
    }
}
