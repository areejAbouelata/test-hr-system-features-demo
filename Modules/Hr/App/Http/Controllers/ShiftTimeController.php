<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Hr\App\Http\Requests\ShiftTimeRequest;
use Modules\Hr\App\Models\ShiftTime;
use Modules\Hr\App\resources\ShiftTimeResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShiftTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):\Inertia\Response
    {
       
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;
        $times = ShiftTime::search($request->search)->paginate($page_size);

        return Inertia::render('Hr::Shift_times/Index', [
            'times' => ShiftTimeResource::collection($times)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftTimeRequest $request): RedirectResponse
    {
        ShiftTime::create($request->validated());
        return redirect()->route('shift_times.index');
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
    public function update(ShiftTimeRequest $request, $id): RedirectResponse
    {
        $time = ShiftTime::find($id);

        $time->update($request->validated());
        return redirect()->route('shift_times.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $time = ShiftTime::find($id);
        $time->forceDelete();
        return redirect()->route('shift_times.index');
    }

    public function RestoreOrDelete(string $id)
    {

        $shiftTime = ShiftTime::find($id);
        if ($shiftTime == null) {
            return redirect()->route('shiftTimes.index')->with('error', 'shiftTime not found');
        }
        if ($shiftTime->deleted_at == null) {
            $shiftTime->deleted_at = now();
        } else {
            $shiftTime->deleted_at = null;
        }
        $shiftTime->save();
        return redirect()->route('shiftTimes.index');
    }

    public function import(Request $request){
        Excel::import(new ShiftTimeImport, 'times.xlsx');

    }

    public function export():BinaryFileResponse
    {

        return Excel::download(new UnitExport, 'units');
    }

}
