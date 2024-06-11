<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Hr\App\Models\Department;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasRoles;


    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMale(Builder $query)
    {
        return $query->where('sex', 'male');
    }
    public function scopeFemale(Builder $query)
    {
        return $query->where('sex', 'female');
    }
    public function shifts()
    {
        return $this->belongsToMany(Shift::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }
    public function employeeShift(){
        return $this->hasOne(EmployeeShift::class,'employee_id','id');
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }


    public function specialTasks(){
        return $this->hasMany(SpecialTask::class);
    }
    public function scopeActive(Builder $query){
        return $query->where(function ($query){
            $query->where('status','active');
               

        });
    }
    public function scopeSearch(Builder $query,$term)
    {
        return $query->when(function ($query) use ($term) {
            $query/* ->where('name', 'like', '%' . $term . '%') */
                /*   ->orWhere('email', 'like', '%' . $term . '%') */
/*                   ->orWhere('phone', 'like', '%' . $term . '%')
 */                  ->orWhere('status', 'like', '%' . $term . '%')
                  ->orWhere('ar_username', 'like', '%' . $term . '%')
                  ->orWhere('en_username', 'like', '%' . $term . '%')
                  ->orWhereRelation('department','name', 'like', '%' . $term . '%')
                  ->orWhereRelation('user','name', 'like', '%' . $term . '%');

          
        });
    }

   
}
