<?php

namespace Modules\Hr\App\resources\EmployeeShift;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class EmployeeShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
      
        $totalWorkHours = 0;
        foreach ($this->attendances as $attendance) {
            $date = Carbon::today()->toDateString(); // or any other appropriate date
            $start = Carbon::parse($date . ' ' . $attendance->start_time);
            $end = Carbon::parse($date . ' ' . $attendance->end_time);
            $totalWorkHours += $end->diffInHours($start);
           

          /*   $totalWorkHours += */
        }

        return [
            
            'id' => $this->id,
            'name' => $this->user?->name ?? $this->employee->ar_username,
            'attendances'=>$this->attendances,
            'total_multiplied_hours' =>  $totalWorkHours,
            'shifts' =>  $this->employeeShift,

            'start_time'=>$this->employeeShift?->shift?->start_time,
           
            'end_time'=>$this->employeeShift?->shift?->end_time,

        ];
    }
}
