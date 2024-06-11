<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\EmployeeDocumentTranslationFactory;

class EmployeeDocumentTranslation extends Model
{
	use HasFactory;

	protected $guarded = ['id', 'created_at', 'updated_at'];
}
