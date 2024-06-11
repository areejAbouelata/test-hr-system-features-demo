<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\AnnualLeaveRequest;
use Modules\Hr\App\Models\AnnualLeave;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\resources\AnnualLeaveResource;

class AnnualLeaveController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
       
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $leaves =  AnnualLeave::search($request->search)->latest()->paginate($page_size);


        return Inertia::render('Hr::Annual_Leave/Index', [
            'leaves' =>  AnnualLeaveResource::collection($leaves),
            'employees'=>Employee::get(),    
          
        ]);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(AnnualLeaveRequest $request): RedirectResponse
    {
      
    
        $leave = AnnualLeave::create($request->validated());

        return redirect()->route('annual_leaves.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AnnualLeaveRequest $request, $id): RedirectResponse
    {
        $leave = AnnualLeave::find($id);
        
        $leave->update($request->validated());

        return redirect()->route('annual_leaves.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shift = AnnualLeave::find($id);
        $shift->forceDelete();
        return redirect()->route('annual_leaves.index');

    }

    public function RestoreOrDelete($id): RedirectResponse
    {
        $leave = AnnualLeave::find($id);
        if ($leave->deleted_at) {

            $leave->restore();
        } else {

            $leave->delete();
        }
        return redirect()->back();
    }


   
}
