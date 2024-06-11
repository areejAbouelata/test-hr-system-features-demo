<?php

namespace App\Http\Requests\EmployeeUser;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSalaryInfo extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'salary' => 'required|numeric',
			'allowance_id' => 'required|array',
			'price' => 'required|array',
			'allowance_id.*' => 'exists:allowances,id',
			'price.*' => 'numeric',
			'shift_id' => 'required|exists:shifts,id'
		];
	}
}
