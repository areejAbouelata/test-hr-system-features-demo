<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeDocumentResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{

		return [
			'id' => $this->id,
			'name' => $this->name,
			'expiration_date' => $this->expiration_date,
			'is_active' => (bool)$this->is_active,
			'created_at' => $this->created_at,
			'translations' => $this->translations,
		];
	}
}
