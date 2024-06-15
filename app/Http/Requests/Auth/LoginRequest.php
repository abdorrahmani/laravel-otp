<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tel' => 'required|numeric|digits:11',
        ];
    }

    public function messages(): array
    {
        return [
            'tel.digits' => 'شماره تلفن صحیح نمی باشد.',
            'tel.required' => 'شماره تلفن را وارد کنید.',
        ];
    }
}
