<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentsResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 */
	public function toArray($request): array
	{

		return [
			'translations' => $this->translations,
			'id' => $this->id,
			'name' => $this->name,
			'short_name' => $this->short_name,
			'description' => $this->description,
			'status' => $this->status,
			'head_of_department' => $this->headOfDepartment,
			'branch_id' => $this->branch_id,
			'updated_at' => $this->updated_at,
			'created_at' => $this->created_at,
			'created_by' => $this->createdBy?->name,
			'updated_by' => $this->updatedBy?->name
		];
	}
}
