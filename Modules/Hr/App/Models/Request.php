<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Request extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['type', 'reason', 'date', 'status', 'user_id', 'manager_id', 'first_choice', 'second_choice', 'code', 'notes', 'file'];

    const STATUS_Waiting = 'waiting';
    const STATUS_APPROVED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const VACATION = 'vacation';
    const JOB = 'job';
    const RESIGNATION = 'resignation';
    const WORK_MISSION = 'work_mission';
    const SICK = 'sick';
    const ABSENCE = 'absence';
    const ASSET = 'asset';
    const ALLOWANCE = 'allowance';
    const EXTRA_WORK = 'extra_work';
    const RETURN_OF_VACATION = 'return_of_vacation';
    const BORROW_MONEY = 'borrow_money';
    const PRINT_FIXUP = 'print_fixup';
    const CANCEL_WORK_MISSION = 'cancel_work_mission';
    const CANCEL_JOB = 'cancel_job';
    const CANCEL_SICK = 'cancel_sick';
    const CANCEL_VACATION = 'cancel_vacation';
    const RETURN_ASSET = 'return_asset';
    const REMOTE = 'remote';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function scopeType(Builder $builder)
    {

        return $builder->when(request('type'), function ($builder) {
            $builder->where('type', 'like', '%' . request('type') . '%');
        });
    }
    public function scopeStatus(Builder $builder)
    {

        return $builder->when(request('status'), function ($builder) {
            $builder->where('status', 'like', '%' . request('type') . '%');
        });
    }

    public function scopeSearch(Builder $builder){
        return $builder->when(request('search'), function ($builder) {
            $builder->whereRelation('user','name', 'like', '%' . request('search') . '%')
            ->orWhereRelation('manager','name', 'like', '%' . request('search') . '%')
            ->orWhere('type','like','%' . request('search') . '%')
            ->orWhere('status','like','%' . request('search') . '%');
        });
    }
}
