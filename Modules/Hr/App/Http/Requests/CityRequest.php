<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $city = $this->city ? $this->city : null;

        $rules = [
            'is_active' => 'required|boolean',
            'country_id' => 'required|exists:countries,id',
            'area_id' => 'required|exists:areas,id',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale . '.name'] = 'required|string|between:3,250|unique:city_translations,name,' . $city . ',city_id';
        }
        return $rules;

    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
