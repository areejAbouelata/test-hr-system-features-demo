<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\JobTitleTranslationFactory;

class JobTitleTranslation extends Model
{
    protected $guarded = ['id','created_at', 'updated_at'];
}
