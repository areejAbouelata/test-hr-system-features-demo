<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Hr\App\Models\Branch;

class AssetTypeRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$asset_type = $this->asset_type ? $this->asset_type : null;

		$rules = [
			'type' => 'required|in:cash,not_cash',
			'is_active' => 'required|boolean',
			'can_apply_for_a_request' => 'required|boolean',
			'branch_id' => 'required|exists:branches,id',

		];
		$languages = Branch::where('id', $this->branch_id)->first()?->branchLanguages()->whereIsActive(true)->get();
		foreach ($languages as $lang) {
			$locale = $lang->short_cut;
			$isDefault = $lang->is_default;
			$rules[$locale . '.name'] = $isDefault ? 'required' : 'nullable' . '|string|between:3,250|unique:asset_type_translations,name,' . $asset_type . ',asset_type_id';
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
