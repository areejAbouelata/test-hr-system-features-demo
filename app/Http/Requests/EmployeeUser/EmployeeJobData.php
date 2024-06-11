<?php

namespace App\Http\Requests\EmployeeUser;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeJobData extends FormRequest
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
			'job_title_id' => 'required|exists:job_titles,id',
			'department_id' => 'required|exists:departments,id',
			'job_level_id' => 'required|exists:job_levels,id',
			'start_date' => 'required|date',
			'manager_id' => 'required|exists:users,id',
			'branch_id' => 'required|exists:branches,id',
		];
	}
}
