<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class LeaveBalance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    Public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
    
    public function scopeSearch(Builder $query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query
            
                ->orWhereRelation('user', 'name', 'like', '%' . $term . '%')
                ->orWhereRelation('user', 'email', 'like', '%' . $term . '%');
        });
    }

   

}
