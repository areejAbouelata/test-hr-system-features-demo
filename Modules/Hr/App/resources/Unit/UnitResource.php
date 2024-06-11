<?php

namespace Modules\Hr\App\resources\unit;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Hr\App\resources\Employees\EmployeeResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {


        $locales = [];
        foreach (config('translatable.locales') as $locale) {
            $locales[$locale]['name'] = $this->translate($locale)?->name;
            $locales[$locale]['description'] = $this->translate($locale)?->description;
        }

        return [
            'translations' => $this->translations,
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'unit_manager_id' => $this->unit_manager_id,
            "unit_manager_name" => $this->unitManager?->user?->name ?? $this->unitManager?->ar_username,
            'created_at' => (string) $this->created_at?->format('Y-m-d'),
            'updated_at' => (string) $this->updated_at?->format('Y-m-d'),
            'created_by' => $this->created_at?->format('Y-m-d') . ' - ' . $this->createdBy?->name,
            'updated_by' => $this->updated_at->format('Y-m-d') . ' - ' . $this->updatedBy?->name,
        ] + $locales;
    }


      
    
}
