<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftScheduleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'old_shift_time_id'=>'nullable|exists:shift_times,id',
            'shift_id'=>'required|exists:shifts,id',
            'day_of_week'=>'required|in:0,1,2,3,4,5,6',
            'shift_time_id'=>'required|exists:shift_times,id',
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
