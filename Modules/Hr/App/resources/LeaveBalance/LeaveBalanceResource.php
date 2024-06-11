<?php

namespace Modules\Hr\App\resources\LeaveBalance;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name'=> $this->user?->name ? $this->user->name : '',
            'annual_entitlement'=> $this->annual_entitlement,
            'opening_balance'   => $this->opening_balance,
            'carry_forward_balance'   => $this->carry_forward_balance,
            'used_leaves_this_year' => $this->used_leaves_this_year,
            'booked_balance'=> $this->booked_balance,
            'manual_entry'=> $this->manual_entry,
            'up_to_date_balance'=> $this->up_to_date_balance,
            'year_end_balance'   => $this->year_end_balance,
            'created_at'  => $this->created_at->format('Y-m-d'),
            'updated_at'  => $this->updated_at->format('Y-m-d'),
            'updated_by'    => $this->updatedBy ? $this->updatedBy->name:'',


        ];
    }
}
