<?php

namespace App\Domain\Product\QueryBuilders;

use App\Domain\Product\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

class ProductCategoryQueryBuilder extends Builder
{
    public function queryBuilderIndex(): QueryBuilder
    {
        return QueryBuilder::for(ProductCategory::class)
        ->allowedFilters(['name']);
    }

    public function queryBuilderShow(): QueryBuilder
    {
        return QueryBuilder::for(ProductCategory::class);
    }

    public function whereId(string $id): self
    {
        return $this->where('id', $id);
    }
}
