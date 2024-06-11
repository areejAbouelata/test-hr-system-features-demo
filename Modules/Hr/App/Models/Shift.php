<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Hr\App\Enums\DayEnum;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends BaseModel implements TranslatableContract
{
    use HasFactory, SoftDeletes,Translatable;
    protected $guarded=[];
    public $translatedAttributes = ['name'];
   

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    public function shiftTimes()
    {
        return $this->belongsToMany(ShiftTime::class)
                    ->withPivot('day_of_week')
                    ->withTimestamps();
    }
    
    public function scopeActive(Builder $query){
        return $query->where(function ($query){
            $query->where('status','active');
               

        });
    }

    public function trans(){
        return $this->hasMany(ShiftTranslation::class);
    }
    public function scopeSearch(Builder $query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->whereRelation('trans','name','like', '%' . $term . '%');
               

        });
    }

    
}
