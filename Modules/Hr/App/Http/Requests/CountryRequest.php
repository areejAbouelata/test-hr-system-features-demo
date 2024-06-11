<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $country = $this->country ? $this->country : null;

        $rules = [
            'phone_code' => 'required|numeric|digits_between:2,5|unique:countries,phone_code,' . $country,
            'flag' => 'nullable|image',
            'phone_limit' => 'required|numeric',
            'is_active' => 'required|boolean' ,
            'is_default' => 'required|boolean' ,
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale . '.name'] = 'required|string|between:3,250|unique:country_translations,name,' . $country . ',country_id';
            $rules[$locale . '.slug'] = 'required|string|between:2,250';
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
