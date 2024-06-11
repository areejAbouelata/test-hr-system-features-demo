<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'description' => 'nullable|string|max:500',
            'employee_id' => 'required|exists:employees,id',
            'created_by'=> 'nullable|exists:users,id',
            'updated_by'=> 'nullable|exists:users,id'
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale . '.name'] = 'required|string|between:3,250|';
        }
        
        if ($this->isMethod('post')) {
            $this->merge(['created_by' => auth()->user()->id]);
        }
        if ($this->isMethod('put')) {
            $this->merge(['updated_by' => auth()->user()->id]);
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
