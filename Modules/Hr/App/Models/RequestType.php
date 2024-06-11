<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Database\factories\RequestTypeFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class RequestType extends BaseModel implements TranslatableContract
{
    use HasFactory,SoftDeletes, Translatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public $translatedAttributes = ['name'];

    protected static function newFactory(): RequestTypeFactory
    {
        //return RequestTypeFactory::new();
    }

  
    
    public function fields(){
        return $this->hasMany(RequestField::class, 'request_type_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }


    


}
