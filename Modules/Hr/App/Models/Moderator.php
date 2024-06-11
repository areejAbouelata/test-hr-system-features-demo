<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Hr\App\Models\Department;
use Illuminate\Database\Eloquent\Builder;

class Moderator extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query
                ->orWhereRelation('user', 'name', 'like', '%' . $term . '%')
                ->orWhereRelation('user', 'email', 'like', '%' . $term . '%')
                ->orWhereRelation('department', 'name', 'like', '%' . $term . '%');

        });
    }

}
