<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\FormalVacationRequest;
use Modules\Hr\App\Models\Department;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\FormalVacation;
use Modules\Hr\App\Models\Unit;
use Modules\Hr\App\resources\FormalVacationResource;

class FormalVacationController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
       
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $vacations =  FormalVacation::search($request->search)->latest()->paginate($page_size);


        return Inertia::render('Hr::Formal_Vacation/Index', [
            'vacations' =>  FormalVacationResource::collection($vacations),
            'employees'=>Employee::get(),    
            'units' => Unit::get(),
            'departments' => Department::get(),
        ]);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(FormalVacationRequest $request): RedirectResponse
    {
      
    
        $vacation = FormalVacation::create($request->validated());

        
        if ($request->has('employee_id')) {
            $this->AssignFormalVacationTo($request, $vacation->id);

        }
        return redirect()->route('formal_vacation.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(FormalVacationRequest $request, $id): RedirectResponse
    {
        $vacation = FormalVacation::find($id);
        
        $vacation->update($request->validated());

        if ($request->has('employee_id')) {
            $this->AssignFormalVacationTo($request, $vacation->id);

        }
        return redirect()->route('formal_vacation.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shift = FormalVacation::find($id);
        $shift->forceDelete();
        return redirect()->route('formal_vacation.index');

    }

    public function RestoreOrDelete($id): RedirectResponse
    {
        $vacation = FormalVacation::find($id);
        if ($vacation->deleted_at) {

            $vacation->restore();
        } else {

            $vacation->delete();
        }
        return redirect()->back();
    }


    public function AssignFormalVacationTo(Request $request, $id): RedirectResponse
    {

        $vacation = FormalVacation::find($id);
        $vacation->employees()->sync($request->employee_id);
        return redirect()->back();
    }
}
