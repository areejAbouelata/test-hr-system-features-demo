<?php

namespace Modules\Hr\App\Http\Requests\Setting\WorkRegoin;

use Illuminate\Foundation\Http\FormRequest;

class WorkRegionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'country_id' => 'required|exist:countries,id',
            'name_en' => 'required|array',
            'name_en.*' => 'string',
            'name_ar' => 'required|array',
            'name_ar.*' => 'string',
            'manager_id' => 'required|array',
            'manager_id.*' => 'exist:users,id',
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
