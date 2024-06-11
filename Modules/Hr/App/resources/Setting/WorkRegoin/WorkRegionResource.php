<?php

namespace Modules\Hr\App\resources\Setting\WorkRegoin;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkRegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'manager_id' => $this->manager,
            'employees' => EmployeeResource::collection($this->employees),
        ];
    }
}
