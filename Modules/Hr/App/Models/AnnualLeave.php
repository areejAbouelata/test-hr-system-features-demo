<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Database\factories\AnnualLeaveFactory;

class AnnualLeave extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    
    protected static function newFactory(): AnnualLeaveFactory
    {
        //return AnnualLeaveFactory::new();
    }


    public function scopeSearch(Builder $query,$term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('number_of_days', 'like', '%' . $term . '%');
                
          
        });
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }


}
