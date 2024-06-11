<?php

namespace Modules\Hr\App\Http\Requests\Setting\WorknigBlock;

use Illuminate\Foundation\Http\FormRequest;

class WorkingBlockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'desc' => 'nullable|text',
            'manager_id' => 'required|exist:users,id',
            'employees_count' => 'required|integer',
        ];
    }
//    public function rules(): array
//    {
//        return [
//            'country_id' => 'required|exist:countries,id',
//            'name_en' => 'required|array',
//            'name_en.*' => 'string',
//            'name_ar' => 'required|array',
//            'name_ar.*' => 'string',
//            'desc' => 'nullable|array',
//            'desc.*' => 'text',
//            'manager_id' => 'required|array',
//            'manager_id.*' => 'exist:users,id' ,
//            'employees_count' => 'required|array' ,
//            'employees_count.*' => 'integer'
//        ];
//    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
