<?php

namespace Modules\Hr\App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class NotificationSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'birth_day_notification' => 'nullable|boolean',
            'anniversary_day_notification' => 'nullable|boolean',
            'new_comer_notification' => 'nullable|boolean',
            'admins_notification' => 'nullable|boolean',
            'employee_notification' => 'nullable|boolean',
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
