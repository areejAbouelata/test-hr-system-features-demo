<?php

namespace Modules\Hr\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalVacationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
           'name'=>'required|string|max:255',
           'type'=>'required|in:paid,unpaid',
           'status'=>'required|in:active,inactive',
           'number_of_days'=>'required|integer|gt:0',
           'vacation_status'=>'required|in:active,inactive',


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
