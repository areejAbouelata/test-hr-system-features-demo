<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\AllowanceTranslationFactory;

class AllowanceTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
	protected $guarded = ['id','created_at', 'updated_at'];

}
