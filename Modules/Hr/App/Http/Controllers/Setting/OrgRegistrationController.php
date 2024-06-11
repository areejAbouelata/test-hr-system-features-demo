<?php

namespace Modules\Hr\App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Services\FlashMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\Setting\OrgRegistration\OrgRegistrationRequest;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\OrgRegistration;
use Modules\Hr\App\resources\Setting\OrgRegistration\OrgRegistrationResource;

class OrgRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $main_org = OrgRegistration::where('is_main_org', true)->latest()->get();
        return Inertia::render('Hr::Settings/OrgRegistration/Index.vue', [
            'org_registrations' => OrgRegistrationResource::collection($main_org),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(OrgRegistrationRequest $request): RedirectResponse
    {
        OrgRegistration::create($request->validated());
        FlashMessage::make()->success(
            message: __('Created Successfully')
        )->closeable()->send();
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
    public function update(OrgRegistrationRequest $request, $id): RedirectResponse
    {
        $org_registration = OrgRegistration::findOrFail($id);
        $org_registration->update($request->validated());
        FlashMessage::make()->success(
            message: __('Updated Successfully')
        )->closeable()->send();
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $org_registration = OrgRegistration::findOrFail($id);
        $org_registration->delete();
        FlashMessage::make()->success(
            message: __('Deleted Successfully')
        )->closeable()->send();
        return redirect()->back();
    }

    public function archive($id, Request $request)
    {
        $org_registration = OrgRegistration::findOrFail($id);
        $org_registration->update(['is_archived' => true]);
        if ($request->org_registration_id) {
            Employee::where(['org_registration_id' => $id])->update(['org_registration_id' => $request->org_registration_id]);
        }
        FlashMessage::make()->success(
            message: __('Archived Successfully')
        )->closeable()->send();
        return redirect()->back();

    }
}
