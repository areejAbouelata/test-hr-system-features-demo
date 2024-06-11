<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\CountryTranslationFactory;

class CountryTranslation extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
	public $timestamps =false;
}
