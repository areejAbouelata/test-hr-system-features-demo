<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Hr\App\Models\Branch;

class JobLevelRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$job_level = $this->job_level ? $this->job_level : null;

		$rules = [
			'is_active' => 'required|boolean',
			'branch_id' => 'required|exists:branches,id',

		];
		$languages = Branch::where('id', $this->branch_id)->first()?->branchLanguages()->whereIsActive(true)->get();
		foreach ($languages as $lang) {
			$locale = $lang->short_cut;
			$isDefault = $lang->is_default;
			$rules[$locale . '.name'] = $isDefault ? 'required' : 'nullable' . '|string|between:3,250|unique:job_level_translations,name,' . $job_level . ',job_level_id';
			$rules[$locale . '.desc'] = 'nullable|string';
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
