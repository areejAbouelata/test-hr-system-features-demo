<?php

namespace Modules\Hr\App\Models;

use App\Models\Concerns\Paginatable;
use App\Models\User;
use Modules\Hr\App\Models\Employee;
use Modules\Hr\App\Models\JobTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Hr\App\Scopes\CompanyScope;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends BaseModel implements TranslatableContract
{
	use HasFactory, SoftDeletes, Translatable, Paginatable;

	protected $guarded = [];
	public $translatedAttributes = ['name', 'description', 'short_name'];

	public function employees()
	{
		return $this->hasMany(Employee::class);
	}

	public function jobTitles()
	{
		return $this->hasMany(JobTitle::class);
	}

	public function headOfDepartment()
	{
		return $this->belongsTo(Employee::class, 'head_of_department', 'id');
	}

	public function scopeActive(Builder $query)
	{
		return $query->whereIsActive(true);
	}

	public function scopeSearch(Builder $query, $term)
	{
		return $query->where(function (Builder $query) use ($term) {
			$query
				->orWhereRelation('headOfDepartment', 'ar_username', 'like', '%' . $term . '%')
				->orWhereHas('translations', function (Builder $query) use ($term) {
					$query->whereAny($this->translatedAttributes, 'like', '%' . $term . '%');
				});
		});
	}

	public function createdBy()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function updatedBy()
	{
		return $this->belongsTo(User::class, 'updated_by');
	}
}
