<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnualLeaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules=[
            'number_of_days' => 'required|integer|min:0|max:365',
            'deduct_type' => 'required|in:work_days,all_days',
            'half_day' => 'required|boolean',
            'balance_type' => 'required|in:all_available_balance,cumulative_balance',
            'year_end_options' => 'required|in:transfer_none,transfer_all,transfer_some',
            'transfer_some_amount' => 'required_if:year_end_options,transfer_some',
            'created_by'=> 'nullable|exists:users,id',
            'updated_by'=> 'nullable|exists:users,id',
        ];
        if($this->method() == 'PUT'){
           $this->merge(['updated_by' => auth()->user()->id]);
        }
        if($this->method() == 'POST'){
            $this->merge(['created_by' => auth()->user()->id]);
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
