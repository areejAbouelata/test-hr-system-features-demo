<?php

namespace Modules\Hr\App\Http\Requests\Departments;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Hr\App\Models\Branch;

class DepartmentsRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$rules = [
			'is_active' => 'boolean',
			'head_of_department' => 'nullable|exists:employees,id',
			'parent_department_id' => 'nullable|exists:departments,id',
			'branch_id' => 'nullable|exists:branches,id',
		];
		$languages = Branch::where('id', $this->branch_id)->first()?->branchLanguages()->whereIsActive(true)->get();
		foreach ($languages as $lang) {
			$locale = $lang->short_cut;
			$isDefault = $lang->is_default;
			$rules[$locale . '.name'] = [$isDefault ? 'required' : 'nullable', 'string', 'between:3,250'];
			$rules[$locale . '.description'] = [$isDefault ? 'required' : 'nullable', 'string', 'between:3,250'];
			$rules[$locale . '.short_name'] = [$isDefault ? 'required' : 'nullable', 'string', 'between:3,250'];
		}
		if ($this->isMethod('post')) {
			$this->merge(['created_by' => auth()->user()->id]);
		}
		if ($this->isMethod('put')) {
			$this->merge(['updated_by' => auth()->user()->id]);
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
