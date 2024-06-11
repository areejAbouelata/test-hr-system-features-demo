<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\AreaTranslationFactory;

class AreaTranslation extends Model
{
	protected $guarded = ['id'];
	public $timestamps = false;
}
