<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\ApproveChainFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApproveChain extends BaseModel implements TranslatableContract
{
    use HasFactory, Translatable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public $translatedAttributes = ['name'];
    protected $casts=[
        'staff_order' =>'json',
    ];

    protected static function newFactory(): ApproveChainFactory
    {
        //return ApproveChainFactory::new();
    }


    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function employees(){
        return $this->belongsToMany(Employee::class,'employee_approve_chain','approve_chain_id','employee_id');
    }

   

}
