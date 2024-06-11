<?php

namespace Modules\Hr\App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Database\factories\AreaFactory;

class Area extends Model implements TranslatableContract
{
    use Translatable , SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
    public $translatedAttributes = ['name'];

	public function cities ()
	{
		return $this->hasMany(City::class);
	}

	public function country()
	{
		return $this->belongsTo(Country::class) ;
	}
}
