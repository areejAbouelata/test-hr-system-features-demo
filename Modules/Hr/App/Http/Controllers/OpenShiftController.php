<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\OpenShiftRequest;
use Modules\Hr\App\Models\OpenShift;
use Modules\Hr\App\resources\OpenShiftResource;

class OpenShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $shifts = OpenShift::query();
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $open_shifts =  $shifts->search($request->search)->paginate($page_size);


        return Inertia::render('Hr::OpenShifts/Index', [
            'shifts' =>  OpenShiftResource::collection($open_shifts),
            
        ]);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(OpenShiftRequest $request): RedirectResponse
    {
        OpenShift::create([
            'name' => $request->name,
            'hours_per_day'=>$request->hours_per_day,
            'flexible'=>$request->flexible,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'created_by'=>auth()->user()->id

        ]);
        return redirect()->route('open_shifts.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(OpenShiftRequest $request, $id): RedirectResponse
    {
        $shift = OpenShift::find($id);
        $shift->update([
            'name' => $request->name,
            'hours_per_day'=>$request->hours_per_day,
            'flexible'=>$request->flexible,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'updated_by'=>auth()->user()->id

        ]);
        return redirect()->route('open_shifts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shift = OpenShift::find($id);
        $shift->forceDelete();
        return redirect()->route('open_shifts.index');

    }

    public function RestoreOrDelete($id): RedirectResponse
    {
        $shift = OpenShift::find($id);
        if ($shift->deleted_at) {

            $shift->restore();
        } else {

            $shift->delete();
        }
        return redirect()->route('open_shifts.index');

    }

}
