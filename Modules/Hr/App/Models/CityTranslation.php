<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\CityTranslationFactory;

class CityTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
	protected $guarded = ['id'];
	public $timestamps =false;
}
