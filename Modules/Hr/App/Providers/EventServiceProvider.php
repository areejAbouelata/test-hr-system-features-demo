<?php

namespace Modules\Hr\App\Providers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Hr\App\Events\EmployeeShift\EmployeeShiftCreated;
use Modules\Hr\App\Listeners\Attendance\CreateAttendanceAfterEmployeeShift;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        EmployeeShiftCreated::class => [
            CreateAttendanceAfterEmployeeShift::class,
        ],
    ];

}
