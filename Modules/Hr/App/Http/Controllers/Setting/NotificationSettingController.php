<?php

namespace Modules\Hr\App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FlashMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\Hr\App\Http\Requests\Setting\NotificationSettingRequest;
use Modules\Hr\App\Http\Requests\Setting\ToggleNotificationSettingRequest;
use Modules\Hr\App\Models\NotificationSetting;
use Modules\Hr\App\Models\UserNotification;
use Modules\Hr\App\resources\Setting\SimpleUserResource;

class NotificationSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notification_settings = NotificationSetting::latest()->first();
        if (!$notification_settings) {
            $notification_settings = NotificationSetting::create([
                'birth_day_notification' => false,
                'anniversary_day_notification' => false,
                'new_comer_notification' => false,
                'admins_notification' => false,
                'employee_notification' => false,
            ]);
        }
        $admins = User::doesntHave('employee')->when($request->key_word, function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->key_word . '%');
        })->latest()->get();
        $employees = User::Has('employee')->latest()->get();
        return Inertia::render('Hr::Settings/Account/CompanyDocuments', [
            'notification_settings' => $notification_settings,
            'admins' => SimpleUserResource::collection($admins),
            'employees' => SimpleUserResource::collection($employees)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(NotificationSettingRequest $request): RedirectResponse
    {
        $notification_settings = NotificationSetting::latest()->first();
        if (!$notification_settings) {
            $notification_settings = NotificationSetting::create($request->validated);
        }
        FlashMessage::make()->success(
            message: __('Notification Updated Successfully')
        )->closeable()->send();
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $notification_settings = NotificationSetting::latest()->first();
        return Inertia::render('Finance::ExpenseCategory/Show', [
            'expenseCategory' => $notification_settings
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     *Toggle user notification
     */
    public function toggleUserNotificationSettings(ToggleNotificationSettingRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        UserNotification::updateOrCreate([
            'user_id' => $request->user_id,
            'user_type' => $request->user_type,
        ], [
            'employee_notification' => $request->employee_notification,
            'admins_notification' => $request->admins_notification,
        ]);
        FlashMessage::make()->success(
            message: __('Updated Successfully')
        )->closeable()->send();
        return redirect()->back();
    }
}
