<?php

namespace Modules\Hr\App\resources\Shifts;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->name,
            'started_at'=>$this->started_at,
            'ended_at'=>$this->ended_at,
            'next_shift_id'=>$this->next_shift_id,
            'shift_times'=>$this->shiftTimes,
            'created_at' => (string) $this->created_at?->format('Y-m-d'),
            'updated_at' => (string) $this->updated_at?->format('Y-m-d'),
            'created_by' => $this->created_at?->format('Y-m-d') . ' - ' . $this->createdBy?->name,
            'updated_by' => $this->updated_at->format('Y-m-d') . ' - ' . $this->updatedBy?->name,
        ] + $locales;
     
    }
}
