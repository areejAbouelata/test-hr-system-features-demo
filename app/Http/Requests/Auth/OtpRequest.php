<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OtpRequest extends FormRequest
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
            'user_name' => 'required',
            'otp_key' => 'required|in:email,phone'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'otp_key' => filter_var($this->user_name, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone'
        ]);
    }
}
