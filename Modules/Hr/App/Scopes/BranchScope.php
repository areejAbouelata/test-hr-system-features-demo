<?php

namespace Modules\Hr\App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $branchId = request()->input('branch_id');

        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
    }
}