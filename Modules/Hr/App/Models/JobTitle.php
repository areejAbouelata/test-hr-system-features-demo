<?php

namespace Modules\Hr\App\Models;

use App\Models\Concerns\Paginatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTitle extends Model
implements TranslatableContract
{
	use Translatable, SoftDeletes, Paginatable;

	protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
	public $translatedAttributes = ['title', 'description'];

	public function department()
	{
		return $this->belongsTo(Department::class);
	}

	public function employees()
	{
		return $this->hasMany(Employee::class);
	}
}
