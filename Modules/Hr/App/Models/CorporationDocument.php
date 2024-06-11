<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Hr\Database\factories\CorporationDocumentFactory;

class CorporationDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    //        TODO delete attachment , upload attachment
    protected function casts(): array
    {
        return [
            'expiration_date' => 'date:Y-m-d',
        ];
    }

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (request()->hasFile('attachment')) {
                $file = request()->file('attachment');
                if ($file->isValid() && $file instanceof UploadedFile) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('images/corporation_documents', $filename, 'public');
                    $model->attachment = $path;
                }
            }
        });
        static::deleting(function ($model) {
            if ($model->attachment) {
                Storage::disk('public')->delete($model->attachment);
            }
        });

    }
}
