<?php

namespace Modules\Hr\App\resources\Assets;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return[
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'user_name' => $this->user?->name ? $this->user->name : '',
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
