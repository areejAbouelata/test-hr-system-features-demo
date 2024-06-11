<?php

namespace Modules\Hr\App\Models;

use App\Models\Concerns\Paginatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\AssetTypeFactory;

class AssetType extends Model implements TranslatableContract
{
	use \Astrotomic\Translatable\Translatable, \Illuminate\Database\Eloquent\SoftDeletes;
	use Paginatable;

	protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
	public $translatedAttributes = ['name'];

}
