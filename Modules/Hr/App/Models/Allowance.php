<?php

namespace Modules\Hr\App\Models;

use App\Models\Concerns\Paginatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hr\Database\factories\AllowanceFactory;

class Allowance extends Model
implements TranslatableContract
{
	use Translatable, SoftDeletes, Paginatable;

	protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
	public $translatedAttributes = ['name', 'desc'];
}
