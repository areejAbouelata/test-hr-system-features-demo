<?php

namespace Modules\Hr\App\Listeners\Attendance;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Hr\App\Events\EmployeeShift\EmployeeShiftCreated;

class CreateAttendanceAfterEmployeeShift
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
    public function handle(EmployeeShiftCreated $event): void
    {
        \Log::info('Some event handled.', ['data' => $event->data]);    
    }
}
