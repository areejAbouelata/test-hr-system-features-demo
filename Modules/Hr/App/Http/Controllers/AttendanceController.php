<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\StoreAttendanceRequest;
use Modules\Hr\App\Models\Attendance as Attendance;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\EmployeeShift;
use Modules\Hr\App\Models\Shift;
use Modules\Hr\App\resources\Attendance\AttendanceResource;

class AttendanceController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
          /*     $this->authorize('viewAny', Attendance::class);  */
          $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

       
        
       $employees = Employee::query()->with('attendances',function($query){
                $query->where('day_date', Carbon::now()->format('Y-m-d'));
        })->paginate(19);
       
       /*  dd($request->start_date); */
   
        return Inertia::render('Hr::Attendances/Index', [
            'employees' =>  AttendanceResource::collection($employees),
            'users' => Employee::get(),
            'shifts' =>  Shift::get()
        ]);
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        /*         $this->authorize('create', Attendance::class); */
        $employee = Employee::where('id', $request->employee_id)->first();
        if (!$employee) {
            return response(['message' => __("hr::attendances.not_found")], Response::HTTP_NOT_FOUND);
        }
        if ($employee->shifts()?->where('shift_id', $request->shift_id)->exists()) {

            $request->merge(['employee_shift_id' => EmployeeShift::where('employee_id', $employee->id)->where('shift_id', $request->shift_id)->first()->id]);
            $request->merge(['day_date' => Carbon::parse($request->day_date)->format('Y-m-d')]);
            $request->merge(['employee_name' => $employee->user?->name ?? $employee->ar_username]);

            $attendance = Attendance::create($request->except('shift_id'));
        } else {
            $employee->shifts()->attach($request->shift_id, [
                'started_at' =>  Carbon::now(),

            ]);
            /*             $pivotEntry = $employee->shifts()->where('shift_id', $request->shift_id)->first()->pivot;
            $request->merge(['employee_shift_id' => $pivotEntry->id]); */
            $request->merge(['employee_shift_id' => EmployeeShift::where('employee_id', $employee->id)->where('shift_id', $request->shift_id)->first()->id]);

            $request->merge(['day_date' => Carbon::parse($request->day_date)->format('Y-m-d')]);
            $request->merge(['employee_name' => $employee->user?->name ?? $employee->ar_username]);


            $attendance = $employee->attendances()->create($request->except('shift_id'));
        }
        return redirect()->route('shifts.employees.attendance');
    }

    public function restDay(Attendance $attendance): RedirectResponse
    {
        /*         $this->authorize('update', $attendance); */
        if ($attendance) {
            $attendance->delete();
        }

        return redirect()->route('shifts.employees.attendance');
    }

    public function RemoveEmployeeShift(Request $request): RedirectResponse
    {
        /*         $this->authorize('update', $attendance); */
        $request->validate([
            'attendance' => 'required|exists:attendances,id',
            'employee' => 'required|exists:employees,id',
        ], [
            'shift_id.exists' => __('hr::attendances.not_found'),
            'employee_id.exists' => __('hr::attendances.not_found'),
        ]);
        $attendance = Attendance::where('employee_id', $request->employee)->where('id', $request->attendance)->first();

        $employee_shift = EmployeeShift::find($attendance->employee_shift_id);

        $employee_shift->delete();
        $attendance->delete();
        return redirect()->route('shifts.employees.attendance');
    }

    public function signing(Attendance $attendance, Request $request){
        $request->validate([
            'sign_in_time'=>'string|required',
            'sign_out_time'=>'string'
        ]);
        $attendance->update([
            'sign_in'=>$request->sign_in_time,
            'sign_out'=>$request->sign_out_time,
            'status'=>'attended'
        ]);
        return redirect()->route('shifts.employees.attendance');
    }
}
