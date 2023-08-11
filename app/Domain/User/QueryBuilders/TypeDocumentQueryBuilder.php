<?php

namespace App\Domain\User\QueryBuilders;

use App\Domain\User\Models\TypeDocument;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class TypeDocumentQueryBuilder extends Builder
{
    public function queryBuilderIndex(): QueryBuilder
    {
        return QueryBuilder::for(TypeDocument::class)
            ->allowedFilters(['name', 'code']);
    }
}
