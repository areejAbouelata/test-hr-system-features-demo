<?php

namespace Modules\Hr\App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FormalVacationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {   
        

        // Assign the parsed dates back to the request
        $this->parseDates();

        $rules = [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'created_by'=> 'nullable|exists:users,id',
            'updated_by'=> 'nullable|exists:users,id'
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale . '.name'] = 'required|string|between:3,250|';
        }
      return $rules;
    }
  

    /**
     * Parse start and end dates.
     */
    protected function parseDates()
    {
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        $this->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
           
        ]);

        if($this->method('PUT') || $this->method('PATCH')){
            $this->merge([
                'updated_by'=>auth()->user()->id
            ]);
        }else if($this->method('POST')){
            $this->merge([
                'created_by'=>auth()->user()->id
            ]);
        }
        
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
