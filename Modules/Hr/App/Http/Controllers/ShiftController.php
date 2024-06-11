<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\Hr\App\Enums\DayEnum;
use Modules\Hr\App\Http\Requests\Shifts\ShiftRequest;
use Modules\Hr\App\Http\Requests\ShiftScheduleRequest;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\EmployeeShift;
use Modules\Hr\App\Models\Shift;
use Modules\Hr\App\Models\ShiftTime;
use Modules\Hr\App\resources\Employees\EmployeeResource;
use Modules\Hr\App\resources\Shifts\ShiftResource;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $shifts = Shift::query();
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $shifts = $shifts->with('shiftTimes')->search($request->search)->paginate($page_size);


        return Inertia::render('Hr::Shifts/Index', [
            'shifts' =>  ShiftResource::collection($shifts),
            'all_shifts' => Shift::get(),
            'times' => ShiftTime::get(),
            'employees' => Employee::get()
        ]);
    }

    
    public function store(ShiftRequest $request): RedirectResponse
    {
      
        
        Shift::create($request->validated());
      /*   [
            'name' => $request->name,
            'started_at' => $started_at,
            'ended_at' => $ended_at,
            'next_shift_id' => $request->next_shift_id,
        ] */
        return redirect()->route('shifts.index');
    }
    public function update(Shift $shift, ShiftRequest $request): RedirectResponse
    {
        if(!$shift){
            return redirect()->route('shifts.index');
        }
        $shift?->update($request->validated());
        return redirect()->route('shifts.index');
    }

    public function destroy(Shift $shift): RedirectResponse
    {
        $shift?->forceDelete();
        return redirect()->route('shifts.index');
    }

    public function RestoreOrDelete(Shift $shift): RedirectResponse
    {
        if ($shift->trashed()) {

            $shift->restore();
        } else {

            $shift->delete();
        }
        return redirect()->back();
    }

    /*     public function assignWorkDayTOShift(ShiftScheduleRequest $request): RedirectResponse
    {
        $shift = Shift::find($request->shift_id);
        $new_shift_time = ShiftTime::find($request->shift_time_id);
        $shift_shift_time = DB::table('shift_shift_time')
        ->where('shift_id', $shift->id)
        ->where('day_of_week', $request->day_of_week)->get();
        $existing_shift = ShiftTime::where('id',$shift_shift_time->shift_time_id)->first();
        if(count($shift_shift_time)>=2){
            return redirect()->route('shifts.index');

        }
        if( $existing_shift->end_time >= $new_shift_time->start_time){
            return redirect()->route('shifts.index');
        }
        if(count($shift_shift_time)<2){
            $data = [
                'shift_id' => $shift->id,
                'shift_time_id' => $request->shift_time_id,
                'day_of_week' => $request->day_of_week,
            ];
        }
      
        DB::table('shift_shift_time')->insert($data);
        return redirect()->route('shifts.index');
    } 
 */
    public function assignWorkDayTOShift(ShiftScheduleRequest $request): RedirectResponse
    {
        $shift = Shift::find($request->shift_id);

        $new_shift_time = ShiftTime::find($request->shift_time_id);

        // Count the number of shift times for the given day
        $count_shift_shift_time = DB::table('shift_shift_time')
            ->where([
                ['shift_id', $shift->id],
                ['day_of_week', $request->day_of_week]
            ])->count();

        // Get the shift time for the given day
        $shift_shift_time = DB::table('shift_shift_time')
            ->where('shift_id', $shift->id)
            ->where('day_of_week', $request->day_of_week)
            ->first();

        if ($shift_shift_time) {
            $existing_shift = ShiftTime::find($shift_shift_time->shift_time_id);
            //max two shifts per day for employee
            if ($count_shift_shift_time >= 2) {
                return redirect()->route('shifts.index');
            }
            // Check if the new shift_time overlaps with the existing shift_time
            if (

                $existing_shift->start_time <= $new_shift_time->end_time &&
                $existing_shift->end_time >= $new_shift_time->start_time
            ) {
                return redirect()->route('shifts.index');
            }

            // Check for overlap across midnight
            /*   if ($existing_shift->start_time > $existing_shift->end_time) {
                if (
                    $new_shift_time->start_time <= $existing_shift->end_time ||
                    $new_shift_time->end_time >= $existing_shift->start_time
                ) {
                    return redirect()->route('shifts.index');
                }
            } */
        }

        // Insert the new shift time if it doesn't overlap
        $data = [
            'shift_id' => $shift->id,
            'shift_time_id' => $request->shift_time_id,
            'day_of_week' => $request->day_of_week,
        ];

        DB::table('shift_shift_time')->insert($data);
        return redirect()->route('shifts.index');
    }
    public function EditScheduleToShift(ShiftScheduleRequest $request): RedirectResponse
    {
        $shift = Shift::find($request->shift_id);
        $shift_shift_time = DB::table('shift_shift_time')->where('shift_id', $shift->id)
            ->where('shift_time_id', $request->old_shift_time_id)
            ->where('day_of_week', $request->day_of_week)->first();
        if ($shift_shift_time) {
            $existing_shift = ShiftTime::find($shift_shift_time->shift_time_id);
          
            $new_shift_time = ShiftTime::find($request->shift_time_id);
            // Check if the new shift_time overlaps with the existing shift_time
            if (
                $existing_shift->start_time <= $new_shift_time->end_time &&
                $existing_shift->end_time >= $new_shift_time->start_time
            ) {
                return redirect()->route('shifts.index');
            }
    
            // Remove the unnecessary first() call before update
            DB::table('shift_shift_time')->where('shift_id', $shift->id)
                ->where('shift_time_id', $request->old_shift_time_id)
                ->where('day_of_week', $request->day_of_week)
                ->update([
                    'shift_time_id' => $request->shift_time_id,
                    'updated_at' => Carbon::now()
                ]);
    
            return redirect()->route('shifts.index');
        }
        
    }



    public function MarkasRestDay(ShiftScheduleRequest $request): RedirectResponse
    {

        $shift = Shift::find($request->shift_id);
        $schedule = DB::table('shift_shift_time')->where('shift_id', $shift->id)
            ->where('shift_time_id', $request->old_shift_time_id)
            ->where('day_of_week', $request->day_of_week)
            ->delete();

        return redirect()->route('shifts.index');
    }
    public function AssignScheduleToEmployee(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id.*' => 'required|exists:employees,id',
            'shift_id' => 'required',
        ]);

        foreach ($request->employee_id as $id) {
            EmployeeShift::create([
                'shift_id' => $request->shift_id,
                'employee_id' => $id,

            ]);
        }

        return redirect()->route('shifts.index');
    }
}
