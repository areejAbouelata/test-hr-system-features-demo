<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Database\factories\UnitFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Unit extends BaseModel implements TranslatableContract
{
    use HasFactory, SoftDeletes, Translatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','description','unit_manager_id','created_by','updated_by'];
    public $translatedAttributes = ['name','description'];

    protected static function newFactory(): UnitFactory
    {
        //return UnitFactory::new();
    }


    public function employees()
    {
        return $this->hasMany(Employee::class,'unit','id');
    }
    public function unitManager(){
        return $this->belongsTo(Employee::class,'unit_manager_id');
    }
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }



    public function scopeSearch(Builder $query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query
                ->orWhereRelation('unitManager', 'ar_username', 'like', '%' . $term . '%')
                ->orWhereRelation('unitManager', 'en_username', 'like', '%' . $term . '%');
        });
    }
}
