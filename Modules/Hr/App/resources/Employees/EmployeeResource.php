<?php

namespace Modules\Hr\App\resources\Employees;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name? $this->user?->name : $this->ar_username,
          
        ];
    }
}
