<?php

use Illuminate\Http\Request;
use Modules\Hr\App\Http\Controllers\DepartmentController;
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Modules\Hr\App\Http\Controllers\ActionLogController;
use Modules\Hr\App\Http\Controllers\AnnualLeaveController;
use Modules\Hr\App\Http\Controllers\ApproveChainController;
use Modules\Hr\App\Http\Controllers\AssetController;
use Modules\Hr\App\Http\Controllers\AttendanceController;
use Modules\Hr\App\Http\Controllers\EmployeeShiftController;
use Modules\Hr\App\Http\Controllers\FileUploadController;
use Modules\Hr\App\Http\Controllers\FormalVacationController;
use Modules\Hr\App\Http\Controllers\HrController;
use Modules\Hr\App\Http\Controllers\LeaveBalanceController;
use Modules\Hr\App\Http\Controllers\ModeratorsController;
use Modules\Hr\App\Http\Controllers\OpenShiftController;
use Modules\Hr\App\Http\Controllers\PermissionController;
use Modules\Hr\App\Http\Controllers\PersonalVacationController;
use Modules\Hr\App\Http\Controllers\RequestController;
use Modules\Hr\App\Http\Controllers\RequestTypeController;
use Modules\Hr\App\Http\Controllers\ShiftController;
use Modules\Hr\App\Http\Controllers\ShiftTimeController;
use Modules\Hr\App\Http\Controllers\StatController;
use Modules\Hr\App\Http\Controllers\TaskController;
use Modules\Hr\App\Http\Controllers\UnitController;
use Modules\Hr\App\Livewire\Departments;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['branch','auth']], function () {
	Route::resource('hr', HrController::class)->names('hr');
	// routes/web.php


	/*     Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
 */
	/*     Route::resource('/departments', DepartmentController::class);
 */
	Route::prefix('departments')->group(function () {
		Route::get('/', [DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:view department');
		Route::post('/', [DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:create department');

		Route::get('/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:create department');
		Route::get('/{department}', [DepartmentController::class, 'show'])->name('departments.show')->middleware('permission:view department');

		Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:update department');
		Route::put('/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:update department');
		Route::put('/{department}/employees', [DepartmentController::class, 'addEmployees'])->name('units.assignEmployees')->middleware('permission:update unit');
		Route::put('/{department}/employees/remove', [DepartmentController::class, 'removeEmployees'])->name('units.removeEmployees')->middleware('permission:update unit');
		Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('departments.delete')->middleware('permission:forceDelete department');
		Route::delete('/{department}/softRestore', [DepartmentController::class, 'RestoreOrDelete'])->name('departments.restore')->middleware('permission:delete department');
		Route::delete('/{department}', [DepartmentController::class, 'RestoreOrDelete'])->name('departments.soft_delete')->middleware('permission:restore department');
	});

	Route::resource('/assets', AssetController::class);



	Route::group(['prefix' => 'requests'], function () {

		Route::get('/', [RequestController::class, 'index'])->name('requests.index');
		Route::get('/create', [RequestController::class, 'create'])->name('requests.create');
		Route::get('/edit/{request}', [RequestController::class, 'edit'])->name('requests.edit');

		Route::group(['prefix' => 'my_requests', 'middleware' => 'permission:view request'], function () {
			Route::get('/', [RequestController::class, 'myRequests'])->name('requests.my_requests');
			Route::get('/create', [RequestController::class, 'createMyRequests'])->name('requests.my_requests.create');
			Route::get('/edit/{request}', [RequestController::class, 'editMyRequests'])->name('requests.my_requests.edit');
		});
	});

	Route::group(['prefix' => 'moderators', 'middleware' => 'permission:view moderator'], function () {
		Route::get('/', [ModeratorsController::class, 'index'])->name('moderators.index');
		Route::get('/{moderator}', [ModeratorsController::class, 'show'])->name('moderators.show');
	});

	Route::group(['prefix' => 'leave_balances', 'middleware' => 'permission:view leavebalance'], function () {
		Route::get('/', [LeaveBalanceController::class, 'index'])->name('leave_balances.index');
		Route::put('/{leave_balance}/manual', [LeaveBalanceController::class, 'updateManual'])->name('leave_balances.updateManual');
		Route::put('/{leave_balance}/annual', [LeaveBalanceController::class, 'updateAnnual'])->name('leave_balances.updateAnnual');
	});


	Route::prefix('/settings')->name('hr.settings.')->group(function () {
		Route::resource('corporation-document', \Modules\Hr\App\Http\Controllers\CorpirationDocumentController::class);
		Route::resource('notification-settings', \Modules\Hr\App\Http\Controllers\Setting\NotificationSettingController::class);
		Route::post('notification-settings-user-toggle', [\Modules\Hr\App\Http\Controllers\Setting\NotificationSettingController::class, 'toggleUserNotificationSettings']);
		Route::resource('country', \Modules\Hr\App\Http\Controllers\CountryController::class);
		Route::resource('area', \Modules\Hr\App\Http\Controllers\AreaController::class);
		Route::resource('city', \Modules\Hr\App\Http\Controllers\CityController::class);
		Route::resource('working-block', \Modules\Hr\App\Http\Controllers\Setting\WorkingBlockController::class);
		//=============work-region========
		Route::resource('work-region', \Modules\Hr\App\Http\Controllers\Setting\WorkingRegionController::class);
		Route::post('move-work-region-employees', [\Modules\Hr\App\Http\Controllers\Setting\WorkingRegionController::class, 'moveEmployeesToAntherWorkRegion']);
		//=============== OrgRegistration ========
		Route::resource('org-registration', \Modules\Hr\App\Http\Controllers\Setting\OrgRegistrationController::class);
		Route::post('archive-org-registration/{id}', [\Modules\Hr\App\Http\Controllers\Setting\OrgRegistrationController::class, 'archive']);

		Route::resource('notification-settings', \Modules\Hr\App\Http\Controllers\Setting\NotificationSettingController::class);
		Route::post('notification-settings-user-toggle', [\Modules\Hr\App\Http\Controllers\Setting\NotificationSettingController::class, 'toggleUserNotificationSettings']);
		Route::resource('country', \Modules\Hr\App\Http\Controllers\CountryController::class);
		Route::resource('working-block', \Modules\Hr\App\Http\Controllers\Setting\WorkingBlockController::class);

		Route::prefix('/account')->name('account.')->group(function () {

			Route::get('/general', function () {
				return view('hr::settings.account.general');
			})->name('general');

			Route::get('/notifications', function () {
				return view('hr::settings.account.notifications');
			})->name('notifications');
		});
		Route::prefix('/organization')->name('organization.')->group(function () {
			Route::get('/org_structure', function () {
				return view('hr::settings.account.notifications');
			})->name('org_structure');
			Route::get('/employee_profile', function () {
				return view('hr::settings.account.notifications');
			})->name('employee_profile');
			Route::get('/bulk_import_sheets', function () {
				return view('hr::settings.account.notifications');
			})->name('bulk_import_sheets');
			Route::get('/assets', function () {
				return view('hr::settings.account.notifications');
			})->name('assets');
		});
	});
	Route::resource('contract-type', \Modules\Hr\App\Http\Controllers\ContractTypeController::class);
	Route::resource('job-title', \Modules\Hr\App\Http\Controllers\JobTitleController::class);
	Route::resource('job-level', \Modules\Hr\App\Http\Controllers\JobLevelController::class);
	Route::resource('allowance', \Modules\Hr\App\Http\Controllers\AllowanceController::class);
	Route::resource('employee-document', \Modules\Hr\App\Http\Controllers\EmployeeDocumentController::class);

	Route::group(['prefix' => 'moderators'], function () {
		Route::get('/', [ModeratorsController::class, 'index'])->name('moderators.index');
		Route::get('/{moderator}', [ModeratorsController::class, 'show'])->name('moderators.show');
	});




	Route::group(['prefix' => 'shifts'], function () {

		Route::get('/', [ShiftController::class, 'index'])->name('shifts.index')->middleware('permission:view shift');
		Route::post('/', [ShiftController::class, 'store'])->name('shifts.store')->middleware('permission:create shift');
		Route::put('/{shift}', [ShiftController::class, 'update'])->name('shifts.update')->middleware('permission:update shift');
		Route::delete('/{shift}', [ShiftController::class, 'destroy'])->name('shifts.delete')->middleware('permission:delete shift');
		Route::post('assignSchedule', [ShiftController::class, 'assignWorkDayTOShift'])->name('shifts.assignSchedule');
		Route::post('EditSchedule', [ShiftController::class, 'EditScheduleToShift'])->name('shifts.EditSchedule');
		Route::post('rest_day', [ShiftController::class, 'MarkasRestDay'])->name('shifts.MarkasRestDay');
		Route::post('assign_to_employees', [ShiftController::class, 'AssignScheduleToEmployee'])->name('shifts.assign_to_employees');

		Route::group(['prefix' => 'employees'], function () {
			Route::get('/', [EmployeeShiftController::class, 'index'])->name('shifts.employees')->middleware('permission:view employeeshift');
			Route::post('/', [EmployeeShiftController::class, 'store'])->name('shifts.employees.store')->middleware('permission:create employeeshift');
			Route::get('/attendance', [AttendanceController::class, 'index'])->name('shifts.employees.attendance')->middleware('permission:view attendance');
			Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store')->middleware('permission:create attendance');

			Route::post('/attendance/removeShift', [AttendanceController::class, 'RemoveEmployeeShift'])->name('attendance.removeEmployeeShift');
			Route::put('/attendance/sign/{attendance}', [AttendanceController::class, 'signing'])->name('attendance.sign');
			Route::post('/attendance/{attendance}', [AttendanceController::class, 'restDay'])->name('attendance.restDay');
		});
	});
	Route::prefix('units')->group(function () {

		Route::get('/', [UnitController::class, 'index'])->name('units.index')->middleware('permission:view unit');
		Route::post('/', [UnitController::class, 'store'])->name('units.store')->middleware('permission:create unit');
		Route::get('/create', [UnitController::class, 'create'])->name('units.create')->middleware('permission:create unit');
		Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit')->middleware('permission:update unit');
		Route::put('/{unit}', [UnitController::class, 'update'])->name('units.update')->middleware('permission:update unit');
		Route::put('/{unit}/employees', [UnitController::class, 'addEmployees'])->name('units.assignEmployees')->middleware('permission:update unit');
		Route::put('/{unit}/employees/remove', [UnitController::class, 'removeEmployees'])->name('units.removeEmployees')->middleware('permission:update unit');
		Route::get('/{unit}', [UnitController::class, 'show'])->name('units.show')->middleware('permission:view unit');
		Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('units.delete')->middleware('permission:forceDelete unit');
		Route::delete('/{unit}/softRestore', [UnitController::class, 'RestoreOrDelete'])->name('units.restore')->middleware('permission:delete unit');
		Route::delete('/{unit}', [UnitController::class, 'RestoreOrDelete'])->name('units.soft_delete')->middleware('permission:restore unit');
		Route::post('/import', [UnitController::class, 'import'])->name('unit.import')->middleware('permission:import unit');
		Route::post('/export', [UnitController::class, 'export'])->name('unit.export');
	});
	Route::prefix('request_types')->group(function () {

		Route::get('/', [RequestTypeController::class, 'index'])->name('request_types.index')->middleware('permission:view requesttypes');
		Route::post('/', [RequestTypeController::class, 'store'])->name('request_types.store')->middleware('permission:create requesttypes');
		Route::put('/{request_type}', [RequestTypeController::class, 'update'])->name('request_types.update')->middleware('permission:update requesttypes');
		Route::delete('/{request_type}', [RequestTypeController::class, 'destroy'])->name('request_types.delete')->middleware('permission:delete requesttypes');
	});
	Route::prefix('shift_times')->group(function () {

		Route::get('/', [ShiftTimeController::class, 'index'])->name('shift_times.index')->middleware('permission:view shifttime');
		Route::post('/', [ShiftTimeController::class, 'store'])->name('shift_times.store')->middleware('permission:create shifttime');
		Route::put('/{shift_time}', [ShiftTimeController::class, 'update'])->name('shift_times.update')->middleware('permission:update shifttime');
		Route::delete('/{shift_time}', [ShiftTimeController::class, 'destroy'])->name('shift_times.delete')->middleware('permission:forceDelete shifttime');
		Route::delete('/{shift_time}/softRestore', [ShiftTimeController::class, 'RestoreOrDelete'])->name('shift_times.restore')->middleware('permission:delete shifttime');
	});
	Route::prefix('shifts')->group(function () {

		Route::get('/', [ShiftController::class, 'index'])->name('shifts.index')->middleware('permission:view shift');
		Route::post('/', [ShiftController::class, 'store'])->name('shifts.store')->middleware('permission:create shift');
		Route::put('/{shift}', [ShiftController::class, 'update'])->name('shifts.update')->middleware('permission:update shift');
		Route::delete('/{shift}', [ShiftController::class, 'destroy'])->name('shifts.delete')->middleware('permission:forceDelete shift');
		Route::delete('/{shift}/softRestore', [ShiftController::class, 'RestoreOrDelete'])->name('shifts.restore')->middleware('permission:delete shift');
	});

	Route::prefix('open_shifts')->group(function () {

		Route::get('/', [OpenShiftController::class, 'index'])->name('open_shifts.index')->middleware('permission:view openshift');
		Route::post('/', [OpenShiftController::class, 'store'])->name('open_shifts.store')->middleware('permission:create openshift');
		Route::put('/{open_shift}', [OpenShiftController::class, 'update'])->name('open_shifts.update')->middleware('permission:update openshift');
		Route::delete('/{open_shift}', [OpenShiftController::class, 'destroy'])->name('open_shifts.delete')->middleware('permission:forceDelete openshift');
		Route::delete('/{open_shift}/softRestore', [OpenShiftController::class, 'RestoreOrDelete'])->name('open_shifts.restore')->middleware('permission:delete openshift');
	});

	Route::prefix('personal_vacations')->group(function () {

		Route::get('/', [PersonalVacationController::class, 'index'])->name('personal_vacation.index')->middleware('permission:view personalvacation');
		Route::post('/', [PersonalVacationController::class, 'store'])->name('personal_vacation.store')->middleware('permission:create personalvacation');
		Route::put('/{personal_vacation}', [PersonalVacationController::class, 'update'])->name('personal_vacation.update')->middleware('permission:update personalvacation');
		Route::delete('/{personal_vacation}', [PersonalVacationController::class, 'destroy'])->name('personal_vacation.delete')->middleware('permission:forceDelete personalvacation');
		Route::delete('/{personal_vacation}/softRestore', [PersonalVacationController::class, 'RestoreOrDelete'])->name('personal_vacation.restore')->middleware('permission:delete personalvacation');
	});
	Route::prefix('formal_vacations')->group(function () {

		Route::get('/', [FormalVacationController::class, 'index'])->name('formal_vacation.index')/* ->middleware('permission:view formalvacation') */;
		Route::post('/', [FormalVacationController::class, 'store'])->name('formal_vacation.store')/* ->middleware('permission:create formalvacation') */;
		Route::put('/{formal_vacation}', [FormalVacationController::class, 'update'])->name('formal_vacation.update')/* ->middleware('permission:update formalvacation') */;
		Route::delete('/{formal_vacation}', [FormalVacationController::class, 'destroy'])->name('formal_vacation.delete')/* ->middleware('permission:forceDelete formalvacation') */;
		Route::delete('/{formal_vacation}/softRestore', [FormalVacationController::class, 'RestoreOrDelete'])->name('formal_vacation.restore')/* ->middleware('permission:delete formalvacation') */;
	});


	Route::get('/actionLogs', [ActionLogController::class, 'getActions'])->name('get.actionLogs');

	Route::post('/file/post', [FileUploadController::class, 'save']);


	Route::prefix('/settings')->name('hr.settings.')->group(function () {
		Route::resource('corporation-document', \Modules\Hr\App\Http\Controllers\CorpirationDocumentController::class);
		Route::resource('notification-settings', \Modules\Hr\App\Http\Controllers\Setting\NotificationSettingController::class);
		Route::post('notification-settings-user-toggle', [\Modules\Hr\App\Http\Controllers\Setting\NotificationSettingController::class, 'toggleUserNotificationSettings']);

		Route::resource('working-block', \Modules\Hr\App\Http\Controllers\Setting\WorkingBlockController::class);
		//=============work-region========
		Route::resource('work-region', \Modules\Hr\App\Http\Controllers\Setting\WorkingRegionController::class);
		Route::post('move-work-region-employees', [\Modules\Hr\App\Http\Controllers\Setting\WorkingRegionController::class, 'moveEmployeesToAntherWorkRegion']);
		//=============== OrgRegistration ========
		Route::resource('org-registration', \Modules\Hr\App\Http\Controllers\Setting\OrgRegistrationController::class);
		Route::post('archive-org-registration/{id}', [\Modules\Hr\App\Http\Controllers\Setting\OrgRegistrationController::class, 'archive']);

		Route::resource('notification-settings', \Modules\Hr\App\Http\Controllers\Setting\NotificationSettingController::class);
		Route::post('notification-settings-user-toggle', [\Modules\Hr\App\Http\Controllers\Setting\NotificationSettingController::class, 'toggleUserNotificationSettings']);
		Route::resource('country', \Modules\Hr\App\Http\Controllers\CountryController::class);
		Route::resource('working-block', \Modules\Hr\App\Http\Controllers\Setting\WorkingBlockController::class);
	});
	Route::resource('contract-type', \Modules\Hr\App\Http\Controllers\ContractTypeController::class);
	Route::resource('job-title', \Modules\Hr\App\Http\Controllers\JobTitleController::class);
	Route::resource('job-level', \Modules\Hr\App\Http\Controllers\JobLevelController::class);

	Route::group(['prefix' => 'moderators'], function () {
		Route::get('/', [ModeratorsController::class, 'index'])->name('moderators.index');
		Route::get('/{moderator}', [ModeratorsController::class, 'show'])->name('moderators.show');
	});

	Route::group(['prefix' => 'permissions'], function () {
		Route::get('/', [PermissionController::class, 'index'])->name('hr.permission.index');
		Route::get('/create', [PermissionController::class, 'create'])->name('hr.role.create');
		Route::post('/store', [PermissionController::class, 'store'])->name('hr.role.store');
		Route::get('/edit/{role}', [PermissionController::class, 'edit'])->name('hr.role.edit');
		Route::put('/edit/{role}', [PermissionController::class, 'update'])->name('hr.role.update');
		Route::delete('delete/{role}', [PermissionController::class, 'destroy'])->name('hr.role.destroy');
		Route::post('assign', [PermissionController::class, 'assignRoleToEmployees'])->name('hr.assignRoleToEmployees');
	});



	Route::group(['prefix' => 'shifts'], function () {

		Route::get('/', [ShiftController::class, 'index'])->name('shifts.index')->middleware('permission:view shift');
		Route::post('/', [ShiftController::class, 'store'])->name('shifts.store')->middleware('permission:create shift');
		Route::put('/{shift}', [ShiftController::class, 'update'])->name('shifts.update')->middleware('permission:update shift');
		Route::delete('/{shift}', [ShiftController::class, 'destroy'])->name('shifts.delete')->middleware('permission:delete shift');

		Route::group(['prefix' => 'employees'], function () {
			Route::get('/', [EmployeeShiftController::class, 'index'])->name('shifts.employees')->middleware('permission:view employeeshift');
			Route::post('/', [EmployeeShiftController::class, 'store'])->name('shifts.employees.store')->middleware('permission:create employeeshift');
			Route::get('/attendance', [AttendanceController::class, 'index'])->name('shifts.employees.attendance')->middleware('permission:view attendance');
			Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store')->middleware('permission:create attendance');

			Route::post('/attendance/removeShift', [AttendanceController::class, 'RemoveEmployeeShift'])->name('attendance.removeEmployeeShift');
			Route::put('/attendance/sign/{attendance}', [AttendanceController::class, 'signing'])->name('attendance.sign');
			Route::post('/attendance/{attendance}', [AttendanceController::class, 'restDay'])->name('attendance.restDay');
		});
	});
	

	Route::prefix('annual_leaves')->group(function () {

		Route::get('/', [AnnualLeaveController::class, 'index'])->name('annual_leaves.index')->middleware('permission:view annualleave');
		Route::post('/', [AnnualLeaveController::class, 'store'])->name('annual_leaves.store')->middleware('permission:create annualleave');
		Route::get('/create', [AnnualLeaveController::class, 'create'])->name('annual_leaves.create')->middleware('permission:create annualleave');
		Route::get('/{annual_leave}/edit', [AnnualLeaveController::class, 'edit'])->name('annual_leaves.edit')->middleware('permission:update annualleave');
		Route::put('/{annual_leave}', [AnnualLeaveController::class, 'update'])->name('annual_leaves.update')->middleware('permission:update annualleave');
		Route::delete('/{annual_leave}', [AnnualLeaveController::class, 'destroy'])->name('annual_leaves.delete')->middleware('permission:delete annualleave');
		Route::delete('/{annual_leave}/softRestore', [AnnualLeaveController::class, 'RestoreOrDelete'])->name('annual_leaves.delete')->middleware('permission:delete annualleave');
	});
	Route::prefix('tasks')->group(function () {

		Route::get('/', [TaskController::class, 'index'])->name('tasks.index')->middleware('permission:view task');
		Route::post('/', [TaskController::class, 'store'])->name('tasks.store')->middleware('permission:create task');
		Route::get('/create', [TaskController::class, 'create'])->name('tasks.create')->middleware('permission:create task');
		Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit')->middleware('permission:update task');
		Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update')->middleware('permission:update task');
		Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.delete')->middleware('permission:delete task');
		Route::delete('/{task}/softRestore', [TaskController::class, 'RestoreOrDelete'])->name('tasks.delete')->middleware('permission:delete task');
	});
	Route::prefix('approve_chains')->group(function () {

		Route::get('/', [ApproveChainController::class, 'index'])->name('approve_chains.index')->middleware('permission:view approvechain');
		Route::post('/', [ApproveChainController::class, 'store'])->name('approve_chains.store')->middleware('permission:create approvechain');
		Route::get('/create', [ApproveChainController::class, 'create'])->name('approve_chains.create')->middleware('permission:create approvechain');
		Route::get('/{chain}/edit', [ApproveChainController::class, 'edit'])->name('approve_chains.edit')->middleware('permission:update approvechain');
		Route::put('/{chain}', [ApproveChainController::class, 'update'])->name('approve_chains.update')->middleware('permission:update approvechain');
		Route::delete('/{chain}', [ApproveChainController::class, 'destroy'])->name('approve_chains.delete')->middleware('permission:delete approvechain');
		Route::delete('/{chain}/softRestore', [ApproveChainController::class, 'RestoreOrDelete'])->name('approve_chains.delete')->middleware('permission:delete approvechain');
	});

	Route::get('/current_branch', function () {
		session()->put('current_branch', 1);
	});


	Route::post('/file/post', [FileUploadController::class, 'save']);
});
