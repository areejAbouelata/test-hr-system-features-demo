<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Hr\App\Exports\UnitExport;
use Modules\Hr\App\Http\Requests\unit\UnitRequest;
use Modules\Hr\App\Imports\UnitImport;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\Unit;
use Modules\Hr\App\resources\Employees\EmployeeResource;
use Modules\Hr\App\resources\unit\UnitResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;
        return Inertia::render('Hr::Units/Index', [
            'units' => UnitResource::collection(Unit::paginate($page_size)),
            'employees' => Employee::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */



    public function store(UnitRequest $request): RedirectResponse
    {
       
        Unit::create($request->validated());

        return redirect()->route('units.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $unit =  Unit::with('employees')->find($id); 
        if ($unit == null) {
            return redirect()->route('units.index')->with('error', 'unit not found');
        }
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $employees = Employee::where('unit', $id)->paginate($page_size);


        return Inertia::render('Hr::Units/Show', [
            'unit' => $unit,
            'unit_employees'=>EmployeeResource::collection($employees),
            'employees' => Employee::get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitRequest $request, string $id): RedirectResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return redirect()->route('units.index')->with('error', 'unit not found');
        }
        $unit->update($request->validated());

        return redirect()->route('units.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return redirect()->route('units.index')->with('error', 'unit not found');
        }
        $unit->forceDelete();
        return redirect()->route('units.index');
    }

    public function RestoreOrDelete(string $id)
    {

        $unit = Unit::find($id);
        if ($unit == null) {
            return redirect()->route('units.index')->with('error', 'unit not found');
        }
        if ($unit->deleted_at == null) {
            $unit->deleted_at = now();
        } else {
            $unit->deleted_at = null;
        }
        $unit->save();
        return redirect()->route('units.index');
    }

    public function removeEmployees(string $id, Request $request){

        $employee = Employee::find($id);
        if ($employee == null) {
            return redirect()->route('units.index')->with('error', 'unit not found');
        }
        $employee->update([
            'unit' => null
        ]);
     
       
        return redirect()->route('units.index');
    }

    public function addEmployees(string $id, Request $request){
        foreach($request->employee_id as $id){
            $employee = Employee::find($id);
            if ($employee == null) {
                return redirect()->route('units.index')->with('error', 'department not found');
            }
            $employee->update([
                'unit' => $request->unit_id
            ]);
        }
       
     
       
        return redirect()->route('units.index');
    }

    public function import(Request $request){
        Excel::import(new UnitImport, 'users.xlsx');

    }

    public function export():BinaryFileResponse
    {

        return Excel::download(new UnitExport, 'units');
    }
}
