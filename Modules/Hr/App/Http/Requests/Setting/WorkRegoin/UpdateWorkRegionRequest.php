<?php

namespace Modules\Hr\App\Http\Requests\Setting\WorkRegoin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkRegionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'country_id' => 'required|exist:countries,id',
            'name_en' => 'required|sting',
            'name_ar' => 'required|string',
            'manager_id' => 'required|exist:users,id',
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
