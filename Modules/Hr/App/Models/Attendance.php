<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\AttendanceFactory;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendances';
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    
    protected static function newFactory(): AttendanceFactory
    {
        //return AttendanceFactory::new();
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function employeeShift(){
        
        return $this->belongsTo(EmployeeShift::class,'employee_shift_id','id');
    } 

}
