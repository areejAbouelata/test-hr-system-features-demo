<?php

namespace Modules\Hr\App\Models;

use App\Models\Concerns\Paginatable;
use App\Models\Concerns\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\JobLevelFactory;

class JobLevel extends Model  implements TranslatableContract
{
	use \Astrotomic\Translatable\Translatable, \Illuminate\Database\Eloquent\SoftDeletes;
	use Paginatable;

	protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
	public $translatedAttributes = ['name', 'desc'];


	public function scopeSearch(Builder $query, ?string $term = null): Builder
	{

		if (empty($term)) {
			$term = request()->input('search');
		}
		return $query->where(function (Builder $query) use ($term) {
			$query->orWhereRelation('translations', 'name', 'like', '%' . $term . '%');
		});
	}
}
