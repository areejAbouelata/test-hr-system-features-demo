<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnualLeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'number_of_days'=> $this->number_of_days,
            'deduct_type' => $this->deduct_type,
            'half_day' =>   $this->half_day,
            'balance_type'=> $this->balance_type,
            'year_end_option'=> $this->year_end_option,
            'transfer_some_amount'=> $this->transfer_some_amount,
            'created_at'=> $this->created_at?->format('Y-m-d'),
            'updated_at'=> $this->updated_at?->format('Y-m-d H:i:s'),
            'created_by'=> $this->createdBy?->name,
            'updated_by'=> $this->updatedBy?->name,
        ];
    }
}
