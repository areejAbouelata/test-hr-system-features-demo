<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;

class BranchLanguageRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'language_id' => 'required|exists:languages,id',
			'branch_id' => 'required|exists:branches,id',
			'is_active' => 'required|boolean'
		];
	}
}
