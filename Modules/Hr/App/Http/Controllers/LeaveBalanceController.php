<?php

namespace Modules\Hr\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FlashMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Hr\App\Enums\LeaveBalanceEnum;
use Modules\Hr\App\Models\LeaveBalance;
use Modules\Hr\App\resources\LeaveBalance\LeaveBalanceResource;

class LeaveBalanceController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $page_size = $request->filled('page_size') ? $request->input('page_size') : 10;
        return Inertia::render('Hr::Leave_Balance/Index', [
            'balances' => LeaveBalanceResource::collection(
                LeaveBalance::search($request->search)->with('user')->paginate($page_size)),
            'numbers' => LeaveBalanceEnum::getAllValues(),
        ]);
    }

    public function updateAnnual(Request $request):RedirectResponse
    {
    /*     $request->validate([
            'annual' => 'required|integer|gt:0|in:' . implode(',', LeaveBalanceEnum::getAllValues())
        ]); */
        $balance = LeaveBalance::find($request->balance_id);

       $this->Year_End_Balance_On_Annual_Entitlement_Change($balance, $request->annual);

        $balance->update([
            'annual_entitlement' => $request->annual,
            'updated_by' => auth()->user()->id
        ]);
        FlashMessage::make()->success(
            message: __('created successfully')
        )->closeable()->send();
        return redirect()->route('leave_balances.index');
    }

    public function updateManual(Request $request): RedirectResponse
    {
        $balance = LeaveBalance::find($request->balance_id);
        $this->Year_End_Balance_On_ManualEntry_Change($balance, $request->manual);

        $balance->update([
            'manual_entry' => $request->manual,
            'updated_by' => auth()->user()->id

        ]);


        return redirect()->route('leave_balances.index');
     
    }

    public function Year_End_Balance_On_ManualEntry_Change(LeaveBalance $balance, $entry)
    {
        if ($balance->manual_entry > $entry) {
            $entry = $balance->manual_entry - $entry;
            $balance->update([
                'up_to_date_balance' => $balance->up_to_date_balance - $entry,
                'year_end_balance' => $balance->year_end_balance - $entry
            ]);
        } else {
            $entry = $entry - $balance->manual_entry;
            $balance->update([
                'up_to_date_balance' => $balance->up_to_date_balance + $entry,
                'year_end_balance' => $balance->year_end_balance + $entry
            ]);
        }
    }

    public function Year_End_Balance_On_Annual_Entitlement_Change(LeaveBalance $balance, $annual)
    {
        if ($balance->annual_entitlement > $annual) {
            $annual = $balance->annual_entitlement - $annual;
            $balance->update([
                'up_to_date_balance' => $balance->up_to_date_balance - $annual,
                'year_end_balance' => $balance->year_end_balance - $annual
            ]);
        } else {
            $annual = $annual - $balance->annual_entitlement;
            $balance->update([
                'up_to_date_balance' => $balance->up_to_date_balance + $annual,
                'year_end_balance' => $balance->year_end_balance + $annual
            ]);
        }
    }
}
