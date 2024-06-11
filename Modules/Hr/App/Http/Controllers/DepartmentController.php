<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\Departments\DepartmentsRequest;
use Modules\Hr\App\Models\branch;
use Modules\Hr\App\Models\Department;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\resources\DepartmentsResource;
use Modules\Hr\App\resources\Employees\EmployeeResource;

class DepartmentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): \Inertia\Response
	{


		return Inertia::render('Hr::Departments/Index', [
			'departments' => DepartmentsResource::collection(
				Department::When($request->branch_id, function ($query) use ($request) {
					return $query->where('branch_id', $request->branch_id);
				})
					->search($request->search)->with('headOfDepartment')->paginate(),

			),
			'employees' => Employee::get(),
			'all_departments' => Department::get()
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(DepartmentsRequest $request): RedirectResponse
	{

		Department::create($request->validated());

		return redirect()->route('departments.index');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		$department =  Department::find($id);
		if ($department == null) {
			return redirect()->route('departments.index')->with('error', 'department not found');
		}
		$employees = Employee::where('department_id', $id)->paginate(20);
		return Inertia::render('Hr::Departments/Show', [
			'department' => $department,
			'department_employees' => EmployeeResource::collection($employees),
			'employees' => Employee::get()
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(DepartmentsRequest $request, string $id)
	{
		$department = Department::find($id);

		if (!$department) {
			return redirect()->route('departments.index')->with('error', 'Department not found');
		}
		$department->update($request->validated());
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$department = Department::find($id);

		if (!$department) {
			return redirect()->route('departments.index')->with('error', 'Department not found');
		}
		$department->forceDelete();
		return redirect()->route('departments.index');
	}

	public function RestoreOrDelete(string $id)
	{

		$department = Department::find($id);
		if ($department == null) {
			return redirect()->route('departments.index')->with('error', 'Department not found');
		}
		if ($department->deleted_at == null) {
			$department->deleted_at = now();
		} else {
			$department->deleted_at = null;
		}
		$department->save();
		return redirect()->route('departments.index');
	}

	public function removeEmployees(string $id, Request $request)
	{

		$employee = Employee::find($id);
		if ($employee == null) {
			return redirect()->route('units.index')->with('error', 'unit not found');
		}
		$employee->update([
			'unit' => null
		]);


		return redirect()->route('units.index');
	}
	public function addEmployees(string $id, Request $request)
	{
		foreach ($request->employee_id as $id) {
			$employee = Employee::find($id);
			if ($employee == null) {
				return redirect()->route('departments.index')->with('error', 'department not found');
			}
			$employee->update([
				'department_id' => $request->department_id
			]);
		}



		return redirect()->route('departments.index');
	}
}
