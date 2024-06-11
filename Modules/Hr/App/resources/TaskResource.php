<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        $locales = [];
		foreach (config('translatable.locales') as $locale) {
			$locales[$locale]['name'] = $this->translate($locale)?->name;
		}
        return [
            'translations' => $this->translations,

            'id' => $this->id,
            'name' => $this->name,
            'employee_id'=>$this->employee?->ar_username,
            'description'=> $this->description,
            'created_at'=>$this->created_at->format('Y-m-d'),
            'updated_at'=>$this->updated_at->format('Y-m-d'),
            'created_by'=>$this->createdBy?->name,
            'updated_by'=>$this->updatedBy?->name
        ]+ $locales;
    }
}
