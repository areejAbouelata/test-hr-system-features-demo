<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\PersonalVacationFactory;

class PersonalVacation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    
    protected static function newFactory(): PersonalVacationFactory
    {
        //return PersonalVacationFactory::new();
    }

    public function scopeSearch(Builder $query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('name','like', '%' . $term . '%');
               

        });
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
}
