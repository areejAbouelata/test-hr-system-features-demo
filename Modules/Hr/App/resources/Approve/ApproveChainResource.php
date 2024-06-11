<?php

namespace Modules\Hr\App\resources\Approve;

use Illuminate\Http\Resources\Json\JsonResource;

class ApproveChainResource extends JsonResource
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
            'staff_order'=>$this->staff_order,
            'description'=> $this->description,
            'created_at'=>$this->created_at->format('Y-m-d'),
            'updated_at'=>$this->updated_at->format('Y-m-d'),
            'employees_count'=>$this->employees ?count($this->employees): 0,
            'staff_count'=>$this->staff_order ? count($this->staff_order): 0,
            'created_by'=>$this->createdBy?->name,
            'updated_by'=>$this->updatedBy?->name
        ]+ $locales;
    }
}
