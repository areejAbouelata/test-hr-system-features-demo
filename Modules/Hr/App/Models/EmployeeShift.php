<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\EmployeeShiftFactory;

class EmployeeShift extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['employee_id','shift_id','started_at','ended_at'];
    protected $table="employee_shift";
    
    protected static function newFactory(): EmployeeShiftFactory
    {
        //return EmployeeShiftFactory::new();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {   
        return $this->belongsTo(Shift::class,'shift_id','id');
    }
}
