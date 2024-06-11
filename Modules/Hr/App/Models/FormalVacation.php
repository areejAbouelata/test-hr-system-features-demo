<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\FormalVacationFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormalVacation extends BaseModel implements TranslatableContract
{
    use HasFactory,Translatable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['id','start_date','end_date','created_at', 'updated_at', 'deleted_at','created_by','updated_by'];
    public $translatedAttributes = ['name'];

    protected static function newFactory(): FormalVacationFactory
    {
        //return FormalVacationFactory::new();
    }

    public function scopeSearch(Builder $query, $term)
    {
       /*  return $query->where(function ($query) use ($term) {
            $query->where('name','like', '%' . $term . '%');
               

        }); */
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function employees(){
        return $this->belongsToMany(Employee::class,'employee_formal_vacations','formal_vacation_id','employee_id');
    }
}
