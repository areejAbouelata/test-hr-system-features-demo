<?php

namespace Modules\Hr\App\Http\Requests\Setting\OrgRegistration;

use Illuminate\Foundation\Http\FormRequest;

class OrgRegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'commercial_registration_number' => 'required|number',
            'commercial_registration_name_ar' => 'required|string',
            'commercial_registration_name_en' => 'required|string',
            'is_main_org' => 'required|boolean',
            'parent_org_id' => 'required_if:is_main_org,true|exist:org_registrations,id',
            'prefix_in_ministry_of_labour' => 'required|number',
            'no_in_ministry_of_labour' => 'required|number',
            'subscription_number' => 'required|number',
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
