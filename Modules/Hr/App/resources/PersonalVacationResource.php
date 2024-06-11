<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalVacationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return[
            'id' => $this->id,
            'name' => $this->name,
            'status'=>$this->status,
            'type'=>$this->type,
            'vacation_status'=>$this->vacation_status,
            'number_of_days'=>$this->number_of_days,
            'created_at'=>$this->created_at->format('Y-m-d'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s'),
            'created_by'=>$this->createdBy?->name,
            'updated_by'=>$this->updatedBy?->name
        ];
    }
}
