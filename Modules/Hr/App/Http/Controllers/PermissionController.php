<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\Permissions\PermissionRequest;
use Modules\Hr\App\Models\branch;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\resources\Employees\EmployeeResource;
use Modules\Hr\App\resources\Permissions\PermissionResource;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): \Inertia\Response
	{
		$page_size = $request->filled('page_size') ? $request->input('page_size') : 10;
		if ($request->has('branch')) {
			session(['current_branch' => $request->branch]);
		}

		$roles = Role::When(session('current_branch'), function ($query) use ($request) {
			$query->where('branch_id', session('current_branch'));
		})->paginate($page_size);

		return Inertia::render('Roles/Index', [
			'roles' => PermissionResource::collection($roles),
			'all_roles' => Role::get(),
			'employees' => Employee::get(),
			'branches' => Branch::get(),
			'permissionsCount' => Permission::count(),
		]);
	}

	public function create(Request $request): \Inertia\Response
	{
		$permissions = Permission::get()->groupBy('group');

		return Inertia::render('Roles/Create', [

			'permissions' => $permissions,
			'branches' => Branch::get(),

		]);
	}

	public function store(PermissionRequest $request): RedirectResponse
	{
		if (Role::where('name', $request->name)->exists()) {
			return redirect()->back()->with('error', 'Role already exists');
		}
		$role = Role::create([
			'name' => $request->name,
			'guard_name' => 'web',
			'company_id' => auth()->user()->company_id,
			'branch_id' => $request->branch_id
		]);

		// Sync the permissions with the role
		$role->permissions()->sync($request->permissions, $request->branch_id);

		return redirect()->route('hr.permission.index');
	}

	/**
	 * Show the form for creating a new resource.
	 */

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
	public function edit(Role $role)
	{


		return Inertia::render('Roles/Create', [
			'role' => $role->load('permissions'),
			'permissions' => Permission::get()->groupBy('group'),
			'branches' => Branch::get(),


		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(PermissionRequest $request, Role $role): RedirectResponse
	{

		if (!$role) {
			return redirect()->back()->with('error', 'Role not found');
		}

		$role->update(['name' => $request->name]);

		$role->permissions()->detach();
		// Assign new permissions
		$role->permissions()->attach($request->input('permissions'));
		return redirect()->route('hr.permission.index')->with('success', 'Permissions updated successfully');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$role = Role::find($id);
		if (!$role) {
			return redirect()->back()->with('error', 'Role not found');
		}
		$role->delete();
	}


	public function employees(): \Inertia\Response
	{
		return Inertia::render('Roles/Employees', [
			'employees' => EmployeeResource::collection(User::get()),
		]);
	}

	public function assignRoleToEmployees(Request $request): RedirectResponse
	{
		
		foreach ($request->employee_id as $id) {
			$employee = Employee::findOrFail($id); 
			$role = Role::findOrFail($request->role_id);
			$branchId = $request->branch_id ? $request->branch_id :$role->branch_id;

			if ($employee->user->roles()->where('id', $role->id)->wherePivot('branch_id', $branchId)->exists()) {
				continue; // Skip if role is already assigned with this branch_id
			}

			$employee->user->roles()->attach($role->id, ['model_type'=>'App\Models\User','branch_id' => $branchId]);

			
			$permissions = $role->permissions->pluck('id')->toArray(); 
			 // Assume Role has many Permissions
			$employee->user->syncPermissionsWithBranches($permissions, $branchId);
		}
		return redirect()->route('hr.permission.index')->with('success', 'roles added to employees successfully');
	}
}
