<?php

namespace Modules\Hr\App\Models;

use App\Models\BranchLanguage;
use App\Models\Concerns\Paginatable;
use App\Models\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Branch extends Model implements TranslatableContract
{
	use Translatable, SoftDeletes;

	protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
	public $translatedAttributes = ['name'];

	public static function boot()
	{
		parent::boot();
		static::saving(function ($model) {
			if (request()->hasFile('image')) {
				$file = request()->file('image');
				if ($file->isValid() && $file instanceof UploadedFile) {
					$filename = time() . '_' . $file->getClientOriginalName();
					$path = $file->storeAs('images/branches', $filename, 'public');
					$model->attachment = $path;
				}
			}
		});
		static::deleting(function ($model) {
			if ($model->attachment) {
				Storage::disk('public')->delete($model->image);
			}
		});
	}

	public function country()
	{
		return $this->belongsTo(Country::class);
	}

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function area()
	{
		return $this->belongsTo(Area::class);
	}

	public function manger()
	{
		return $this->belongsTo(User::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function branchLanguages()
	{
		return $this->hasMany(BranchLanguage::class);
	}
}
