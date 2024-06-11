<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\BaseModelFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Hr\App\Observers\BaseModelObserver;
use Modules\Hr\App\Scopes\CompanyScope;
use Modules\Hr\App\Scopes\WithoutTrashedScope;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new WithoutTrashedScope);
        static::observe(BaseModelObserver::class);
        static::bootPersonalPermissionFilter();
        static::BootBranchId();

        /*        
            static::bootCompanyId();
          static::addGlobalScope(new CompanyScope);
        */
    }

    /**
     * Add company_id to the model.
     *
     * @return void
     */
    protected static function bootCompanyId()
    {
        static::creating(function ($model) {

            $model->created_by = auth()->user()->id;
        });


        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }
    protected static function BootBranchId()
    {
        static::addGlobalScope('branch_id', function ($query) {
            $query->where('branch_id', session('current_branch') ? session('current_branch') : auth()->user()->branch_id);
        });
    }



    /**
     * Sees if the user has personal or department permission for this resource type and applies it as a query scope
     */
    protected static function bootPersonalPermissionFilter()
    {
        static::addGlobalScope('personalPermission', function ($query) {
            if (Auth::check()) {
                $modelName = class_basename(get_called_class());
                $personalPermission = 'personal ' . strtolower($modelName);
                $departmentPermission = 'department ' . strtolower($modelName);
                $userRoles = Auth::user()->getRoleByBranchID();
             
               

                    foreach ($userRoles as $role) {
                      
                        if ($role->hasPermissionTo($personalPermission) && !$role->hasPermissionTo($departmentPermission)) {
                            // Filter by created_by if the user has personal permission
                            $query->where('created_by', Auth::id());
                        } elseif ($role->hasPermissionTo($departmentPermission) && !$role->hasPermissionTo($personalPermission)) {
                            // Filter by department's employees if the user has department permission
                            $departmentEmployeeIds = Employee::where('department_id', Auth::user()->employee->department_id)
                                ->pluck('user_id');
                            $query->whereIn('created_by', $departmentEmployeeIds);
                        } else {
                            // If user doesn't have personal or department permission or have both, return the query as is
                            return $query;
                        }
                    }
                
                
            }
        });
    }
}
