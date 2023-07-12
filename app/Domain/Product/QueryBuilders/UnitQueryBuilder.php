<?php

namespace App\Domain\Product\QueryBuilders;

use App\Domain\Product\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

class UnitQueryBuilder extends Builder
{
    public function queryBuilderIndex(): QueryBuilder
    {
        return QueryBuilder::for(Unit::class)
            ->allowedFilters(['name', 'code']);
    }

    public function queryBuilderShow(): QueryBuilder
    {
        return QueryBuilder::for(Unit::class);
    }

    public function whereCode(string $code): self
    {
        return $this->where('code', $code);
    }
}
