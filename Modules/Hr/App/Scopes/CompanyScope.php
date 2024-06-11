<?php

namespace Modules\Hr\App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if(auth()->check()){
            $builder->where('company_id', auth()->user()->company_id);

        }
    }
}
