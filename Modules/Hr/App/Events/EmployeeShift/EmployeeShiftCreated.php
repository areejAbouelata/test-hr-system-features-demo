<?php

namespace Modules\Hr\App\Events\EmployeeShift;

use Illuminate\Foundation\Events\Dispatchable;

use Illuminate\Queue\SerializesModels;
use Modules\Hr\App\Models\EmployeeShift;

class EmployeeShiftCreated
{
    use SerializesModels, Dispatchable;

    /** @var EmployeeShift */
    public $employee_shift;
    /**
     * Create a new event instance.
     */
    public function __construct(EmployeeShift $employeeShift)
    {
       $this->employee_shift = $employeeShift;
    }

  
}
