<?php

namespace Modules\Hr\App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\Setting\WorknigBlock\WorkingBlockRequest;
use Modules\Hr\App\Models\Country;
use Modules\Hr\App\Models\WorkingBlock;
use Modules\Hr\App\resources\Setting\SimpleUserResource;

class WorkingBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $working_blocks = WorkingBlock::latest()->paginate(25);
        $managers = User::all();
        return Inertia::render('Hr::Settings/WorkingBlock/Index.vue', [
            'working_blocks' => $working_blocks,
            'managers' => $managers
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
//    public function store(WorkingBlockRequest $request): RedirectResponse
//    {
//            $working_block_data = $request->validated();
//            foreach ($working_block_data['name_ar'] as $key=>$value ){
//                WorkingBlock::create([
//                    'name_ar' => $value ,
//                    'country_id' => $request->counry_id ,
//                    'name_en' => $request->name_en[$key] ,
//                    'desc' => isset($request->desc[$key]) ?$request->desc[$key]: null,
//                    'manager_id' => isset($request->manager_id[$key]) ?$request->manager_id[$key]: null,
//                    'employees_count' => isset($request->employees_count[$key]) ?$request->employees_count[$key]: null,
//                ]);
//            }
//    }
    public function store(WorkingBlockRequest $request): RedirectResponse
    {
        $working_block_data = $request->validated();
        WorkingBlock::create([$working_block_data]);
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('hr::show');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(WorkingBlockRequest $request, $id): RedirectResponse
    {
        $working_block = WorkingBlock::findOrFail($id);
        $working_block->update($request->validated());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $working_block = WorkingBlock::findOrFail($id);
        $working_block->delete();
        return redirect()->back() ;
    }
}
