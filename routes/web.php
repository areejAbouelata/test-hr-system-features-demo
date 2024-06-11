<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Modules\Hr\App\Http\Controllers\BranchController;
use Modules\Hr\App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth'])->group(function () {
   Route::get('/', function () {
      return inertia('Welcome');
   })->name('home');
   Route::get('/dashboard', function () {
      return inertia('Dashboard/Index');
   })->name('dashboard');
   Route::resource('language', \App\Http\Controllers\Language\LanguageController::class);

   Route::any('/set-language/{lang}', function (Request $request, $lang) {
      session()->put('locale', $lang);

      $request->user()->update([
         'current_locale' => $lang
      ]);
      return back();
   })->name('language');
});

Auth::routes();
Route::get('forget_password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('auth.forget_password');
Route::post('send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('auth.send_otp');
Route::get('verify/{user_name}', [ForgotPasswordController::class, 'showVerifyOtpForm'])->name('auth.verify_otp_form');
Route::post('verify', [ForgotPasswordController::class, 'verifyOtp'])->name('auth.verify_opt');

Route::get('login', [LoginController::class, 'create'])->name('login');


/* -------------------------------------------------------------------------- */
/*                                    roles                                   */
/* -------------------------------------------------------------------------- */
Route::group(['prefix' => 'roles'], function () {
   Route::get('/', [PermissionController::class, 'index'])->name('roles.index');
   Route::get('/create', [PermissionController::class, 'create'])->name('roles.create');
   Route::post('/store', [PermissionController::class, 'store'])->name('roles.store');
   Route::get('/edit/{role}', [PermissionController::class, 'edit'])->name('roles.edit');
   Route::put('/edit/{role}', [PermissionController::class, 'update'])->name('roles.update');
   Route::delete('delete/{role}', [PermissionController::class, 'destroy'])->name('roles.destroy');
   Route::post('/assign', [PermissionController::class, 'assignRoleToEmployees'])->name('roles.assignRoleToEmployees');
});
/* -------------------------------------------------------------------------- */
/*                                  branches                                  */
/* -------------------------------------------------------------------------- */
Route::group(['prefix' => 'branches', 'middleware' => 'permission:view branch'], function () {
   Route::get('/', [BranchController::class, 'index'])->name('branches.index');
   Route::post('/', [BranchController::class, 'store'])->name('branches.store');
   Route::put('/', [BranchController::class, 'update'])->name('branches.update');
   Route::get('/create', [BranchController::class, 'create'])->name('branches.create');
   Route::get('/{branch}', [BranchController::class, 'show'])->name('branches.show');
   Route::get('/edit/{branch}', [BranchController::class, 'edit'])->name('branches.edit');
   Route::get('/destroy/{branch}', [BranchController::class, 'create'])->name('branches.destroy');
   Route::post('/add-language', [\App\Http\Controllers\Language\BranchLanguageController::class, 'addLanguage'])->name('branches.toggle-language');
   Route::get('/languages/{branch_id}', [\App\Http\Controllers\Language\BranchLanguageController::class, 'getLanguages']);
   Route::put('/user-language', [\App\Http\Controllers\Language\BranchLanguageController::class, 'changeUiLanguage']);
});

/* -------------------------------------------------------------------------- */
/*                           Employee                           */
/* -------------------------------------------------------------------------- */
Route::group(['middleware' => 'permission:view branch'], function () {
   Route::resource('employees', \App\Http\Controllers\EmployeeUser\EmployeeController::class);
   Route::group(['prefix' => 'employee'], function () {
      Route::post('validate-general-data', [\App\Http\Controllers\EmployeeUser\EmployeeController::class, 'validateGeneralData']);
      Route::post('validate-iqamah-data', [\App\Http\Controllers\EmployeeUser\EmployeeController::class, 'validateIqamahData']);
      Route::post('validate-salary-data', [\App\Http\Controllers\EmployeeUser\EmployeeController::class, 'validateSalaryInfo']);
      Route::post('validate-job-data', [\App\Http\Controllers\EmployeeUser\EmployeeController::class, 'validateJobData']);
   });
});


/* -------------------------------------------------------------------------- */
/*                           countries cities areas                           */
/* -------------------------------------------------------------------------- */
Route::resource('country', \Modules\Hr\App\Http\Controllers\CountryController::class);
Route::resource('area', \Modules\Hr\App\Http\Controllers\AreaController::class);
Route::resource('city', \Modules\Hr\App\Http\Controllers\CityController::class);

// for testing vue components
Route::get('/components', function () {
   return inertia('Welcome');
})->name('vue.components');
