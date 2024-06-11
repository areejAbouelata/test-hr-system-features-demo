<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\Approve\ApproveChainRequest;
use Modules\Hr\App\Models\ApproveChain;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\resources\Approve\ApproveChainResource;

class ApproveChainController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
       
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;

        $chains =  ApproveChain::latest()->paginate($page_size);


        return Inertia::render('Hr::Approve_Chains/Index', [
            'chains' =>  ApproveChainResource::collection($chains),
            'employees'=>Employee::get(),    
          
        ]);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(ApproveChainRequest $request): RedirectResponse
    {
      
    
        $chain = ApproveChain::create($request->validated());
        if($request->has('employee_id')){
            $this->AssignApproveChainToEmployee($request,$chain->id);
        }
       
        return redirect()->route('approve_chains.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ApproveChainRequest $request, $id): RedirectResponse
    {
        $chain = ApproveChain::find($id);
        
        $chain->update($request->validated());
        if($request->has('employee_id')){
            $this->AssignApproveChainToEmployee($request,$chain->id);
        }
      
        return redirect()->route('approve_chains.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       $chain = ApproveChain::find($id);
       $chain->forceDelete();
        return redirect()->route('approve_chains.index');

    }

    public function RestoreOrDelete($id): RedirectResponse
    {
        $chain = ApproveChain::find($id);
        if ($chain->deleted_at) {

            $chain->restore();
        } else {

            $chain->delete();
        }
        return redirect()->back();
    }

    public function AssignApproveChainToEmployee($request,$chain_id)
    {
        $chain = ApproveChain::find($chain_id);
        $chain->employees()->sync($request->employee_id);
    }

   

}
