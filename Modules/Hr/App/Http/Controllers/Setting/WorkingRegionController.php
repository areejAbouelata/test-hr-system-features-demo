<?php

namespace Modules\Hr\App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Services\FlashMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\Setting\WorkRegoin\MoveEmployeesToAntherWorkRegionRequest;
use Modules\Hr\App\Http\Requests\Setting\WorkRegoin\UpdateWorkRegionRequest;
use Modules\Hr\App\Http\Requests\Setting\WorkRegoin\WorkRegionRequest;
use Modules\Hr\App\Models\Country;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\WorkRegions;
use Modules\Hr\App\resources\Setting\WorkRegoin\WorkRegionResource;

class WorkingRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $working_regions = WorkRegions::when($request->country_id, function ($q) use ($request) {
            $q->where('country_id', $request->country_id);
        })->latest()->paginate(20);
        $country = Country::all();
//        TODO
//        countries => regions => employees
        return Inertia::render('Hr::Settings/WorkRegion/Index.vue', [
            'working_regions' => WorkRegionResource::collection($working_regions),
            'countries' => $country
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkRegionRequest $request): RedirectResponse
    {
        $working_region_data = $request->validated();
        foreach ($working_region_data['name_ar'] as $key => $value) {
            WorkRegions::create([
                'country_id' => $request->counry_id,
                'name_ar' => $value,
                'name_en' => $request->name_en[$key],
                'manager_id' => isset($request->manager_id[$key]) ? $request->manager_id[$key] : null,
            ]);
        }
        FlashMessage::make()->success(
            message: __('Created Successfully')
        )->closeable()->send();
        return redirect()->back();    }

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
    public function update(UpdateWorkRegionRequest $request, $id): RedirectResponse
    {
        $work_region = WorkRegions::findOrFail($id);
        $work_region->update($request->validated());
        FlashMessage::make()->success(
            message: __('Updated Successfully')
        )->closeable()->send();
        return redirect()->back();    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $work_region = WorkRegions::findOrFail($id);
        $work_region->delete();
        FlashMessage::make()->success(
            message: __('Deleted Successfully')
        )->closeable()->send();
        return redirect()->back();
    }

    public function moveEmployeesToAntherWorkRegion(MoveEmployeesToAntherWorkRegionRequest $request)
    {
        if ($request->is_same_location) {
            Employee::whereIn('id', $request->employee_ids)->update([
                'work_region_id' => $request->work_region_id[0]
            ]);
        } else {
            foreach ($request->employee_ids as $key => $user_id)
                Employee::where('id', $user_id)->update([
                    'work_region_id' => $request->work_region_id[$key]
                ]);
        }
        FlashMessage::make()->success(
            message: __('Moved Successfully')
        )->closeable()->send();
        return redirect()->back();
    }
}
