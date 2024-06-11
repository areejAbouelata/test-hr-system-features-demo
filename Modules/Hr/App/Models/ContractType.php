<?php

namespace Modules\Hr\App\Models;

use App\Models\Concerns\Paginatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractType extends Model  implements TranslatableContract
{
	use HasFactory, Paginatable;

	/**
	 * The attributes that are mass assignable.
	 */
	use Translatable, SoftDeletes;

	protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
	public $translatedAttributes = ['name'];
}
