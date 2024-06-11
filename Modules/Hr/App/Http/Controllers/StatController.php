<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Models\Asset;
use Modules\Hr\App\Models\AssetType;
use Modules\Hr\App\Models\Attendance;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\resources\Stats\AttendanceStatsResource;

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('hr::index');
    }

    
    public function Attendances(Request $request): \Inertia\Response
    {
        $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();

        /*         $startDate = Carbon::now()->startOfMonth();
 */
        $endDate = $startDate->copy()->endOfMonth();
        // Retrieve attendance records for the month
        $attendanceRecords = Attendance::whereBetween('day_date', [$startDate, $endDate])
            ->get();
        $totalWorkingDays = Attendance::whereBetween('day_date', [$startDate, $endDate])
            ->count();
        $absenteeismByDay = $attendanceRecords->groupBy('day_date')
            ->map(function ($records) {
                return $records->where('status', 'absent')->count();
            });

      
        $totalAbsentees = $absenteeismByDay->sum();
        $absenteeismPercentage = ($totalAbsentees / $totalWorkingDays) * 100;

        return Inertia::render('Hr::Stats/Attendance', [
            'attendances' =>     $totalAbsentees,
            'absenteeismPercentage' =>  $absenteeismPercentage,
        ]);
    }

    public function employees(){
        $saudi_employees = Employee::where('nationality', 'Saudi Arabia')->count();
        $other_employees = Employee::whereNot('nationality', 'Saudi Arabia')->count();
        $saudi_precentage = ($saudi_employees / ($saudi_employees + $other_employees)) * 100;
        $other_precentage = ($other_employees / ($saudi_employees + $other_employees)) * 100;

        return Inertia::render('Hr::Stats/Employees', [
            'saudi_employees' => $saudi_employees,
            'other_employees' => $other_employees,
            'saudi_percentage' => $saudi_precentage,
            'other_percentage' => $other_precentage
        ]);
    }

    public function assets(){
        $assets = Asset::count();
        $assets_with_users = Asset::whereNotNull('user_id')->count();
        $assets_with_no_user = Asset::whereNull('user_id')->count();
        $assets_in_maintenance = Asset::where('status', 'in_maintenance')->count();
        $asset_types = AssetType::get();
        $types=[];
        foreach($asset_types as $asset_type){
            $types[$asset_type->name] = Asset::where('asset_type_id', $asset_type->id)->count();
            $types['unused'.$asset_type->name] = Asset::where('asset_type_id', $asset_type->id)
            ->where('user_id', null)->count();

        }
      /*   $assets_type= Asset::where('asset_type_id')->count(); */
        return Inertia::render('Hr::Stats/Assets',[
            'assets' => $assets,
            'assets_with_users' => $assets_with_users,
            'assets_with_no_user' => $assets_with_no_user,
            'assets_in_maintenance' => $assets_in_maintenance,
            'types' => $types,

        ]);
    }



}
