<?php

namespace Modules\Hr\App\resources\Setting\WorkRegoin;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->user?->id ,
            'employee_id' => $this->id ,
            'name' => $this->user?->name ,
            'image' => $this->user?->image ,
        ];
    }
}
