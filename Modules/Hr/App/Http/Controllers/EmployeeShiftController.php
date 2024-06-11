<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\Shift;
use Inertia\Inertia;
use Modules\Hr\App\Events\EmployeeShift\EmployeeShiftCreated;
use Modules\Hr\App\Http\Requests\EmployeeShift\CreateRequest as EmployeeShiftCreateRequest;
use Modules\Hr\App\Models\Attendance;
use Modules\Hr\App\Models\EmployeeShift;
use Modules\Hr\App\resources\EmployeeShift\EmployeeShiftResource;

class EmployeeShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $shifts = Shift::query()->with('employees');
        /*         $employees = Employee::query()->with('employeeShift.shift')->with('attendances'); */
        $carbonDate = Carbon::parse($request->start_date);
        $startDate = $carbonDate ? $carbonDate->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $endDate =  $carbonDate ? $carbonDate->copy()->addDays(6)->format('Y-m-d') : Carbon::now()->addDays(7)->format('Y-m-d');
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;
        
        $employees = Employee::query()
            ->search($request->search)
            ->with('employeeShifts.shift')
            ->with(['attendances' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('day_date', [$startDate, $endDate])->get();
            }])->paginate($page_size);
        $shifts = $shifts->get();

        return Inertia::render('Hr::EmployeeShifts/Index', [
            'employees' => EmployeeShiftResource::collection($employees),
            'shifts' =>  $shifts
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hr::create');
    }

    /**
     * Store a newly created resource in storage.
     */



    public function store(EmployeeShiftCreateRequest $request): RedirectResponse
    {
        $shift = Shift::find($request->shift_id);
        if (!$shift) {
            return redirect()->back()->withInput()->withErrors(['shift_error' => 'Shift not found']);
        }

        $startDate = Carbon::parse($request->started_at)->toDateString();
        $endDate = Carbon::parse($request->ended_at)->toDateString();

        if ($this->hasOverlappingShifts($request->employee_id, $startDate, $endDate)) {
            return redirect()->back()->withInput()->withErrors(['overlap_error' => 'Employee shifts cannot overlap']);
        }

        $employeeShift = $this->createEmployeeShift($request->employee_id, $request->shift_id, $startDate, $endDate);
        // Create Attendance records for each day ==> to be refactored into an event
        $this->createAttendanceRecords($employeeShift, $startDate, $endDate, $shift->start_time, $shift->end_time);

        return redirect()->route('shifts.employees');
    }



    private function hasOverlappingShifts($employeeId, $startDate, $endDate)
    {
        return EmployeeShift::where('employee_id', $employeeId)
            ->where(function ($query) use ($startDate, $endDate) {
                // Check for shifts that start or end within the new shift's date range
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('started_at', [$startDate, $endDate])
                        ->orWhereBetween('ended_at', [$startDate, $endDate]);
                })
                    // Check for shifts that entirely encompass the new shift's date range
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('started_at', '<=', $startDate)
                            ->where('ended_at', '>=', $endDate);
                    });
            })->exists();
    }
    private function createEmployeeShift($employeeId, $shiftId, $startDate, $endDate)
    {
        foreach($employeeId as $employee){
            $employeeShift = EmployeeShift::create([
                'employee_id' => $employee,
                'shift_id' => $shiftId,
                'started_at' => $startDate,
                'ended_at' => $endDate
            ]);
        }
      /*   return EmployeeShift::create([
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'started_at' => $startDate,
            'ended_at' => $endDate
        ]); */
    }

    private function createAttendanceRecords($employeeShift, $startDate, $endDate, $startTime, $endTime)
    {
        $startDate = Carbon::parse($startDate);
        $differenceInDays = $startDate->diffInDays($endDate);

        for ($i = 0; $i <= $differenceInDays; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            Attendance::create([
                'day_date' => $currentDate,
                'employee_shift_id' => $employeeShift->id,
                'employee_id' => $employeeShift->employee_id,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
        }
    }
    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('hr::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('hr::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
