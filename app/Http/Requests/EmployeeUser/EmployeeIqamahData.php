<?php

namespace App\Http\Requests\EmployeeUser;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeIqamahData extends FormRequest
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
			'address' => 'require|string' ,
		];
	}
}
