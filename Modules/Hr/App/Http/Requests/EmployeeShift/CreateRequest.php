<?php

namespace Modules\Hr\App\Http\Requests\EmployeeShift;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
           'employee_id' => 'required|exists:employees,id',
           'shift_id' => 'required|exists:shifts,id',
           'started_at' => 'required|string',
           'ended_at' => 'nullable|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
