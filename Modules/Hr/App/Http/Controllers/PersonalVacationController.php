<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\PersonalVacationRequest;
use Modules\Hr\App\Models\PersonalVacation;
use Modules\Hr\App\resources\PersonalVacationResource;

class PersonalVacationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
       
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $vacations =  PersonalVacation::search($request->search)->paginate($page_size);


        return Inertia::render('Hr::Personal_Vacation/Index', [
            'vacations' =>  PersonalVacationResource::collection($vacations),
            
        ]);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonalVacationRequest $request): RedirectResponse
    {
        $request->merge(['created_by'=>auth()->user()->id]);
        PersonalVacation::create($request->all());
        return redirect()->route('personal_vacation.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PersonalVacationRequest $request, $id): RedirectResponse
    {
        $vacation = PersonalVacation::find($id);
        $request->merge(['updated_by'=>auth()->user()->id]);

        $vacation->update($request->except('vacation_id'));
        return redirect()->route('personal_vacation.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shift = PersonalVacation::find($id);
        $shift->forceDelete();
        return redirect()->route('personal_vacation.index');

    }

    public function RestoreOrDelete($id): RedirectResponse
    {
        $shift = PersonalVacation::find($id);
        if ($shift->deleted_at) {

            $shift->restore();
        } else {

            $shift->delete();
        }
        return redirect()->back();
    }

}
