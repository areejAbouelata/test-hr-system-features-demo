<?php

namespace Modules\Hr\App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$branch = $this->branch ? $this->branch->id : null;

		$rules = [
			'country_id' => 'required|exists:countries,id',
			'city_id' => 'required|exists:cities,id',
			'area_id' => 'required|exists:areas,id',
			'manager_id' => 'required|exists:users,id',
			'address' => 'required|string',
			'employees_count' => 'nullable|integer',
			'is_active' => 'required|boolean',
			'image' => 'nullable|file',
			'email' => 'required|email',
			'phone' => 'required|string',
			'inbox_number' => 'required|string',
			'fax_number' => 'required|string',
			'website' => 'required|url',
			'language_id' => 'required|exists:languages,id'
		];
		foreach (config('translatable.locales') as $locale) {
			$rules[$locale . '.name'] = 'required|string|between:3,250|unique:branch_translations,name,' . $branch . ',branch_id';
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
