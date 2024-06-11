<?php

namespace Modules\Hr\App\resources\Setting\OrgRegistration;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildOrgRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id ,
            'commercial_registration_number' => $this->commercial_registration_number ,
            'commercial_registration_name_ar' => $this->commercial_registration_name_ar ,
            'commercial_registration_name_en' => $this->commercial_registration_name_en ,
            'is_main_org' => $this->is_main_org ,
            'prefix_in_ministry_of_labour' => $this->prefix_in_ministry_of_labour ,
            'no_in_ministry_of_labour' => $this->no_in_ministry_of_labour ,
            'subscription_number' => $this->subscription_number ,

        ];
    }
}
