<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\OrgRegistrationFactory;

class OrgRegistration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function childOrgRegistrations()
    {
        return $this->hasMany(OrgRegistration::class, 'parent_org_id');
    }
}
