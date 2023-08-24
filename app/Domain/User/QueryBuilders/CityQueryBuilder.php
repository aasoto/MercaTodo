<?php

namespace App\Domain\User\QueryBuilders;

use App\Domain\User\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

class CityQueryBuilder extends Builder
{
    public function queryBuilderIndex(): QueryBuilder
    {
        return QueryBuilder::for(City::class)
            ->allowedFilters(['name'])
            ->allowedIncludes(['state']);
    }
}
