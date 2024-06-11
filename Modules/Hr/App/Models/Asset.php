<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['name','status','user_id','description'];

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }
    public function scopeUnactive(Builder $query)
    {
        return $query->where('status', 'inactive');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query,$term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('name', 'like', '%' . $term . '%')
                  ->orWhere('status', 'like', '%' . $term . '%')
                  ->orWhereRelation('user','name', 'like', '%' . $term . '%');

          
        });
    }

}
