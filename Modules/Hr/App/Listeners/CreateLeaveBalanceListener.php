<?php

namespace Modules\Hr\App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hr\App\Models\LeaveBalance;

class CreateLeaveBalanceListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        //create default leave balance
        LeaveBalance::create([
            'user_id' => $event->employee?->user?->id,
            'annual_entitlement'=> 21,
            'opening_balance' => 0,
            'carry_forward_balance' => 0,
            'used_leaves_this_year' => 0,
            'booked_balance' =>0,
            'manual_entry' => 0,
            'up_to_date_balance' => 0,
            'year_end_balance' => 0,
        ]);
    }
}
