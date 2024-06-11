<?php

namespace Modules\Hr\App\Http\Requests;

use App\Models\BranchLanguage;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Hr\App\Models\Branch;

class AllowanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
		$allowance = $this->allowance ? $this->allowance : null;

		$rules = [
			'is_active' => 'required|boolean' ,
			'branch_id' => 'required|exists:branches,id',

		];

		$languages = Branch::where('id', $this->branch_id)->first()?->branchLanguages()->whereIsActive(true)->get();
		foreach ($languages as $lang) {
			$locale  = $lang->short_cut;
			$isDefault = $lang->is_default;
			$rules[$locale . '.name'] = $isDefault ? 'required' : 'nullable' . '|string|between:3,250|unique:allowance_translations,name,' . $allowance . ',allowance_id';
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
