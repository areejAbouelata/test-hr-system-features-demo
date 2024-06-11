<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Database\factories\RequestFieldFactory;

class RequestField extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    
    protected static function newFactory(): RequestFieldFactory
    {
        //return RequestFieldFactory::new();
    }
    public function options(){
        return $this->hasMany(ChoiceOption::class,'request_field_id','id');
    }

}
