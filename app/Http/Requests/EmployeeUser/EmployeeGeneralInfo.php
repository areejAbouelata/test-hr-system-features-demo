<?php

namespace App\Http\Requests\EmployeeUser;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeGeneralInfo extends FormRequest
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
		];
	}
}
