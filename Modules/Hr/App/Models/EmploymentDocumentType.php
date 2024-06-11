<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\EmploymentDocumentTypeFactory;

class EmploymentDocumentType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): EmploymentDocumentTypeFactory
    {
        //return EmploymentDocumentTypeFactory::new();
    }
}
