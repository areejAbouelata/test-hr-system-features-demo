<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Hr\App\Models\Branch;

class ContractTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $contract_type = $this->contract_type ? $this->contract_type : null;

        $rules = [
            'is_active' => 'required|boolean' ,
            'branch_id' => 'required|exists:branches,id',

        ];
		$languages = Branch::where('id', $this->branch_id)->first()?->branchLanguages()->whereIsActive(true)->get();
		foreach ($languages as $lang) {
			$locale  = $lang->short_cut;
			$isDefault = $lang->is_default;
			$rules[$locale . '.name'] = $isDefault ? 'required' : 'nullable' . '|string|between:3,250|unique:contract_type_translations,name,' . $contract_type . ',contract_type_id';
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
