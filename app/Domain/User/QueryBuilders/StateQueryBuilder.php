<?php

namespace App\Domain\User\QueryBuilders;

use App\Domain\User\Models\State;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

class StateQueryBuilder extends Builder
{
    public function queryBuilderIndex(): QueryBuilder
    {
        return QueryBuilder::for(State::class)
            ->allowedFilters(['name']);
    }
}
