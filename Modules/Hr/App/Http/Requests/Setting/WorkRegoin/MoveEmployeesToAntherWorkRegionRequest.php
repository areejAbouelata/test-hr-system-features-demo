<?php

namespace Modules\Hr\App\Http\Requests\Setting\WorkRegoin;

use Illuminate\Foundation\Http\FormRequest;

class MoveEmployeesToAntherWorkRegionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'is_same_location' => 'required|boolean',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:users,id',
            'work_region_id' => 'required|array',
            'work_region_id.*' => 'exists:work_regions,id', // one index if the same
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
