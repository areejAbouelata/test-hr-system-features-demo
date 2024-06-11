<?php

namespace Modules\Hr\App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class WithoutTrashedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {

        $builder->withoutTrashed();
    }
}
