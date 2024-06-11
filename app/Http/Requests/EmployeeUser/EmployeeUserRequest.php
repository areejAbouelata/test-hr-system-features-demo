<?php

namespace App\Http\Requests\EmployeeUser;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUserRequest extends FormRequest
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
			'name' => 'required|string',
			'image' => 'required|file',
			'note' => 'required|string',
			'email' => 'required|email',
			'password' => 'required|string|min:6',
			'default_local' => 'required|exists:languages,id', //languages
			'is_active' => 'required|boolean',
			'is_ban' => 'required|boolean',
			'birth_date' => 'required|date',
			'gender' => 'required|in:male,female',
			'country_id' => 'required',
			'civilization_state' => 'required"string|in:resident,citizen',
			'employing_number' => 'required|numeric',
			'id_number' => 'required',
			'nationality' => 'required',
			'iqama_expiration_date' => 'required|date',
			'passport' => 'required',
			'attachments' => 'required|array',
			'attachments.*' => 'required|file',
			'attachment_name' => 'required|array',
			'attachment_name.*' => 'string',
			'address' => 'require|string',
			'job_title_id' => 'required|exists:job_titles,id',
			'department_id' => 'required|exists:departments,id',
			'job_level_id' => 'required|exists:job_levels,id',
			'start_date' => 'required|date',
			'manager_id' => 'required|exists:users,id',
			'branch_id' => 'required|exists:branches,id',
			'salary' => 'required|numeric',
			'allowance_id' => 'required|array',
			'price' => 'required|array',
			'allowance_id.*' => 'exists:allowances,id',
			'price.*' => 'numeric',
			'shift_id' => 'required|exists:shifts,id'
		];
	}
}
