<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetTypeResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'type' => $this->type,
			'is_active' => (bool)$this->is_active,
			'can_apply_for_a_request' => (bool)$this->can_apply_for_a_request,
			'created_at' => $this->created_at ? $this->created_at : null,
			'translations' => $this->translations,
		];
	}
}
