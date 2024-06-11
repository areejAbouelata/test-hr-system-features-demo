<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\WorkRegionsFactory;

class WorkRegions extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'work_region_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manger_id');
    }
}
