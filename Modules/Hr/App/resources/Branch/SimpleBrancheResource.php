<?php

namespace Modules\Hr\App\resources\Branch;

use Illuminate\Http\Resources\Json\JsonResource;

class SimpleBrancheResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
			'languages'=> $this->branchLanguages()->where('status', 'active')->get()
		];
    }
}
