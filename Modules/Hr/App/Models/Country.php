<?php

namespace Modules\Hr\App\Models;

use App\Models\Concerns\Paginatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Hr\Database\factories\CountryFactory;

class Country extends Model implements TranslatableContract
{
	use Translatable, SoftDeletes, Paginatable;

	public static function boot()
	{
		parent::boot();
		static::saving(function ($model) {
			if (request()->hasFile('flag')) {
				$file = request()->file('flag');
				if ($file->isValid() && $file instanceof UploadedFile) {
					$filename = time() . '_' . $file->getClientOriginalName();
					$path = $file->storeAs('images/countries', $filename, 'public');
					$model->flag = $path;
				}
			}
		});
		static::deleting(function ($model) {
			if ($model->flag) {
				Storage::disk('public')->delete($model->flag);
			}
		});
	}

	protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
	public $translatedAttributes = ['name'];

	public function cities()
	{
		return $this->hasMany(City::class);
	}
	public function areas()
	{
		return $this->hasMany(Area::class);
	}
}
