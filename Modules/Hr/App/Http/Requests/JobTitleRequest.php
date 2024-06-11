<?php

namespace Modules\Hr\App\Http\Requests;

use App\Models\BranchLanguage;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Hr\App\Models\Branch;

class JobTitleRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$job_title = $this->job_title ? $this->job_title : null;

		$rules = [
			'is_active' => 'required|boolean',
			'branch_id' => 'required|exists:branches,id',

		];
		$languages = Branch::where('id', $this->branch_id)->first()?->branchLanguages()->whereIsActive(true)->get();
		foreach ($languages as $lang) {
			$locale  = $lang->short_cut;
			$isDefault = $lang->is_default;
			$rules[$locale . '.title'] = $isDefault ? 'required' : 'nullable' . '|string|between:3,250|unique:job_title_translations,name,' . $job_title . ',job_title_id';
			$rules[$locale . '.description'] = 'nullable|string';
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
