<?php

namespace Modules\Hr\App\resources\Stats;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceStatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {

        // Calculate total working days in the month (for simplicity, excluding weekends)


        // Calculate total absentees for the month

      
     

        return [
            'total_absentees' => $totalAbsentees,
            'total_working_days' => $totalWorkingDays,
            'absenteeism_percentage' => $absenteeismPercentage,
            'absenteeism_by_day' => $absenteeismByDay,
        ];
    }
}
