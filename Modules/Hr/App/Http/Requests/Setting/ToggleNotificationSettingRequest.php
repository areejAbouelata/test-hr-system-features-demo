<?php

namespace Modules\Hr\App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class ToggleNotificationSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'user_type' => 'required|in:admin,employee',
            'employee_notification' => 'required_if:user_type,employee',
            'admins_notification' => 'required_if:user_type,admin',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
