<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Database\factories\SpecialTaskFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Builder;

class SpecialTask extends Model implements TranslatableContract
{
    use HasFactory,SoftDeletes,Translatable;
    protected $fillable = ['id','description','employee_id','created_at', 'updated_at', 'deleted_at','created_by','updated_by'];
    public $translatedAttributes = ['name'];
    /**
     * The attributes that are mass assignable.
     */
    
    protected static function newFactory(): SpecialTaskFactory
    {
        //return SpecialTaskFactory::new();
    }

    public function scopeSearch(Builder $query, $term)
    {
       return $query->where(function ($query) use ($term) {
            $query->where('description','like', '%' . $term . '%')
            ->orWhereRelation('createdBy', 'name', 'like', '%' . $term . '%');
               

        }); 
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }

}
