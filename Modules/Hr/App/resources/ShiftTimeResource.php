<?php

namespace Modules\Hr\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class ShiftTimeResource extends JsonResource
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
            'start_time' =>  $this->start_time,
            'end_time' =>  $this->end_time,
            'next_day'  => (Boolean)  $this->next_day ?? false,
            'work_hour_duration'  =>   $this->work_hour_duration,
            'flexibility'   =>   $this->flexibility,
            'minutes_compensation'   =>   $this->minutes_compensation,
            'before_sign_in'    =>   $this->before_sign_in,
            'after_sign_in'     =>   $this->after_sign_in,
            'before_sign_out'    =>   $this->before_sign_out,
            'after_sign_out'     =>   $this->after_sign_out,
            'allowed_delay_for_sign_in'     =>   $this->allowed_delay_for_sign_in,
            'allowed_delay_for_sign_out'     =>   $this->allowed_delay_for_sign_out,
            'overtime'   =>   $this->overtime,
            'max_overtime_duration'   =>   $this->max_overtime_duration,
          
            'created_at' => (string) $this->created_at?->format('Y-m-d'),
            'updated_at' => (string) $this->updated_at?->format('Y-m-d'),
            'created_by' => $this->created_at?->format('Y-m-d') . ' - ' . $this->createdBy?->name,
            'updated_by' => $this->updated_at->format('Y-m-d') . ' - ' . $this->updatedBy?->name,
        ] + $locales;
    }
}
