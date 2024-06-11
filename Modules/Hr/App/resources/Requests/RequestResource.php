<?php

namespace Modules\Hr\App\resources\Requests;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'reason'=> $this->reason,
            'user_name'=> $this->user?->name,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,

        ];
    }
}
