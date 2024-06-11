<?php

namespace App\Http\Controllers\EmployeeUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeUser\EmployeeGeneralInfo;
use App\Http\Requests\EmployeeUser\EmployeeIqamahData;
use App\Http\Requests\EmployeeUser\EmployeeJobData;
use App\Http\Requests\EmployeeUser\EmployeeSalaryInfo;
use App\Http\Requests\EmployeeUser\EmployeeUserRequest;
use App\Http\Resources\EmployeeUser\EmployeeResource;
use App\Models\EmployeeAllowance;
use App\Models\EmployeeAttachment;
use App\Models\EmployeeUser;
use App\Models\User;
use App\Services\FlashMessage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Mockery\Exception;
use Modules\Hr\App\Models\Allowance;
use Modules\Hr\App\Models\Branch;
use Modules\Hr\App\Models\Country;
use Modules\Hr\App\Models\Department;
use Modules\Hr\App\Models\JobLevel;
use Modules\Hr\App\Models\JobTitle;
use Modules\Hr\App\Models\Shift;
use Modules\Hr\App\resources\CountryResource;
use Modules\Hr\App\resources\Setting\SimpleUserResource;

class EmployeeController extends Controller
{
	public function index(Request $request)
	{
		$EmployeeUseres = EmployeeUser::latest()->paginate();
		return Inertia::render('Hr::Employees/Index', [
			'employees' => EmployeeResource::collection($EmployeeUseres)
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Request $request)
	{
		$country = Country::all();
		$job_titles = JobTitle::when($request->branch_id, function ($q) use ($request) {
			$q->where('branch_id', $request->branch_id);
		})->get();
		$departments = Department::when($request->branch_id, function ($q) use ($request) {
			$q->where('branch_id', $request->branch_id);
		})->get();
		$job_levels = JobLevel::when($request->branch_id, function ($q) use ($request) {
			$q->where('branch_id', $request->branch_id);
		})->get();
		$branches = Branch::all();
		$allowances = Allowance::when($request->branch_id, function ($q) use ($request) {
			$q->where('branch_id', $request->branch_id);
		})->get();
		$shifts = Shift::when($request->branch_id, function ($q) use ($request) {
			$q->where('branch_id', $request->branch_id);
		})->get();
		$mangers = User::latest()->get();

		return Inertia::render('Hr::Employees/Create', [
			'mangers' => $mangers,
			'countries' => $country,
			'jobTitles' => $job_titles,
			'departments' => $departments,
			'jobLevels' => $job_levels,
			'branches' => $branches,
			'allowances' => $allowances,
			'shifts' => $shifts,
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(EmployeeUserRequest $request)
	{
		//		user
		$user = User::create($request->only(['name', 'email', 'phone'])->merge(['user_type' => 'employee', 'password' => bcrypt($request->password)]));
		//		employee
		EmployeeUser::create($request->validated()->except(['attachments', 'attachment_name', 'allowance_id', 'price', 'password', 'image'])->merge(['user_id' => $user->id]));
		//		allownces
		$i = 0;
		foreach ($request->allowance_id as $allowance_id) {

			EmployeeAllowance::create(['user_id' => $user->id, 'allowance_id' => $allowance_id, 'price' => isset($request->price[$i]) ? $request->price[$i] : 0]);
			$i = $i++;
		}
		//		attachments
		$j = 0;
		foreach ($request->attachments as $attachment) {
			$employee_attachemnt = EmployeeAttachment::create(['employee_id' => $user->id, 'attachment_name' => isset($request->attachment_name[$j]) ? $request->attachment_name[$j] : null]);
			$file = $attachment;
			if ($file->isValid() && $file instanceof UploadedFile) {
				$filename = time() . '_' . $file->getClientOriginalName();
				$path = $file->storeAs('images/employees', $filename, 'public');
				$employee_attachemnt->attachment = $path;
				$employee_attachemnt->save();
			}
			$j = $j++;
		}

		FlashMessage::make()->success(
			message: __('created successfully')
		)->closeable()->send();
		return redirect()->back();
	}


	/**
	 * Show the specified resource.
	 */
	public function show(EmployeeUser $EmployeeUser)
	{
		return Inertia::render('Hr::Employees/Show', [
			'employee' => EmployeeResource::make($EmployeeUser),
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(EmployeeUser $EmployeeUser)
	{
		$countries = Country::with('areas.cities')->latest()->get();
		//        mangers TODO how to identify and add EmployeeUser id
		$mangers = User::latest()->get();
		return Inertia::render('Hr::Employees/Create', [
			'countries' => CountryResource::collection($countries),
			'users' => SimpleUserResource::collection($mangers),
			'employee' => EmployeeResource::make($EmployeeUser),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(EmployeeUserRequest $request, $id)
	{
		$EmployeeUser = EmployeeUser::findOrFail($id);
		$EmployeeUser->update($request->validated());
		FlashMessage::make()->success(
			message: __('updated successfully')
		)->closeable()->send();
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		// validate delete
		$EmployeeUser = EmployeeUser::findOrfail($id);
		try {
			$EmployeeUser->delete();
			FlashMessage::make()->success(
				message: __('Deleted successfully')
			)->closeable()->send();
			return redirect()->back();
		} catch (Exception $exception) {
			FlashMessage::make()->error(__('EmployeeUser Has Related Data'))->send();
			return redirect()->back();
		}
	}

	public function validateGeneralData(EmployeeGeneralInfo $request)
	{
		return redirect()->back();
	}

	public function validateIqamahData(EmployeeIqamahData $request)
	{
		return redirect()->back();
	}

	public function validateSalaryInfo(EmployeeSalaryInfo $request)
	{
		return redirect()->back();
	}

	public function validateJobData(EmployeeJobData $request)
	{
		return redirect()->back();
	}
}
