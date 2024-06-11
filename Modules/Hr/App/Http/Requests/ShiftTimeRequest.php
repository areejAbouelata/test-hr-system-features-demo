<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftTimeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
           
            'start_time' => 'required',
            'end_time' => 'required',
            'next_day' => 'required|boolean',
            'work_hour_duration' => 'nullable',
            'flexibility' => 'required|boolean',
            'minutes_compensation' => 'nullable|integer|min:0',
            'before_sign_in' => 'nullable|integer|min:0',
            'after_sign_in' => 'nullable|integer|min:0',
            'before_sign_out' => 'nullable|integer|min:0',
            'after_sign_out' => 'nullable|integer|min:0',
            'allowed_delay_for_sign_in' => 'nullable|integer|min:0',
            'allowed_delay_for_sign_out' => 'nullable|integer|min:0',
            'overtime' => 'required|boolean',
            'max_overtime_duration' => 'nullable|integer|min:0',
            'branch_id'=> 'nullable|exists:branches,id',
            'created_by'=> 'nullable|exists:users,id',
            'updated_by'=> 'nullable|exists:users,id',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale . '.name'] = 'required|string|between:3,250|';
        }
        if ($this->isMethod('post')) {
            $this->merge(['created_by' => auth()->user()->id]);
            $this->merge(['branch_id' => session('current_branch') ]);
            
        }
        if ($this->isMethod('put')) {
            $this->merge(['updated_by' => auth()->user()->id]);
            $this->merge(['branch_id' => session('current_branch') ]);

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
