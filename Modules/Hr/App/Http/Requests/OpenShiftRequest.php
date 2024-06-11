<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenShiftRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
public function rules(): array
    {
        return [
            'name'=>'required|string|max:255',
            'hours_per_day'=>'required|integer|min:1|max:24',
            'flexible'=>'nullable|boolean',
            'start_time'=>'nullable|string',
            'end_time'=>'nullable|string',

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
