<?php

namespace Modules\Hr\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Hr\Database\factories\CandidateEmployeeFactory;

class CandidateEmployee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function scopeSearch(Builder $query,$term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('candidate_name', 'like', '%' . $term . '%');
        });
    }
}

