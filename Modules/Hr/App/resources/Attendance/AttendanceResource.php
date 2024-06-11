<?php

namespace Modules\Hr\App\resources\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Hr\App\Models\Employee;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = null;
        $day_date = null;
        $attendance_id = null;
        $attendance_sign_in = null;
        $attendance_sign_out = null;
        $difference = null;
        $start_time = null;
        $end_time = null;
        $difference_between_work_hours = null;

        foreach ($this->attendances as $attendance) {

            $sign_in = Carbon::parse($attendance->sign_in);
            $sign_out = $attendance->sign_out ? Carbon::parse($attendance->sign_out) : null;
            $difference = $sign_out ? $sign_out->diffInHours($sign_in) : null;
            $status = $attendance->status;
            $day_date = $attendance->day_date;
            $attendance_id = $attendance->id;
            $attendance_sign_in =     $attendance->sign_in;
            $attendance_sign_out = $attendance->sign_out;

            $start_time = $attendance->start_time;


            $end_time = $attendance->end_time;
            $start_time_carbon = Carbon::parse( $start_time);
            $end_time_carbon = Carbon::parse($end_time);
            $shift_work_hours = $start_time_carbon->diffInHours($end_time_carbon);
            $difference_between_work_hours = $difference - $shift_work_hours;
        }


        return [

            'employee_name' => $this->user ? $this->user->name : Employee::where('id', $this->employee_id)->first()->user?->name,
            'employee_id' => $this->id,
            'status' => $status ? $status : null,
            'attendance_id' => $attendance_id,
            'sign_in' => $attendance_sign_in,
            'sign_out' => $attendance_sign_out,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'day_date' => $day_date,
            'work_hours' => $difference,
            'difference_between_work_hours' => $difference_between_work_hours
            /*           'start_time'=>$this->attendances? $this->employeeShift->shift->start_time : null,
            'end_time'=>$this->attendances? $this->employeeShift->shift->end_time : null,
 */
        ];
    }
}
