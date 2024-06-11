<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\JobLevelTranslationFactory;

class JobLevelTranslation extends Model
{
    protected $guarded = ['id','created_at', 'updated_at'];

}
