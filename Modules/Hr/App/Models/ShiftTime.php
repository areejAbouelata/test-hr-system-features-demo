<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Database\factories\ShiftTimeFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class ShiftTime extends Basemodel implements TranslatableContract
{
    use HasFactory, SoftDeletes, Translatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public $translatedAttributes = ['name'];

    protected static function newFactory(): ShiftTimeFactory
    {
        //return ShiftTimeFactory::new();
    }
 public function shifts()
    {
        return $this->belongsToMany(Shift::class)
                    ->withPivot('day_of_week')
                    ->withTimestamps();
    }

   /*  public function translations(){

        return $this->hasMany(ShiftTimeTranslation::class);
    } */
    public function scopeSearch(Builder $query, $term)
    {
       /*  return $query->where(function ($query) use ($term) {
            $query
                ->whereRelation('translations','name', 'like', '%' . $term . '%');
                
        }); */
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }


}
