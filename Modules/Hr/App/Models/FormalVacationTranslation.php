<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\FormalVacationTranslationFactory;

class FormalVacationTranslation extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    
    protected static function newFactory(): FormalVacationTranslationFactory
    {
        //return FormalVacationTranslationFactory::new();
    }
}
