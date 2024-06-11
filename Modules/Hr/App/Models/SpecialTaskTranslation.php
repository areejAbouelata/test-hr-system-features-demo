<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\SpecialTaskTranslationFactory;

class SpecialTaskTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    protected $table ='special_tasks_translations';
    
    protected static function newFactory(): SpecialTaskTranslationFactory
    {
        //return SpecialTaskTranslationFactory::new();
    }
}
