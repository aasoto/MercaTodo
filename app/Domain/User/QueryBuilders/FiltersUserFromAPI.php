<?php

namespace App\Domain\User\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersUserFromAPI implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query -> where('users.first_name', 'like', '%'.$value.'%')
        ->orWhere('users.second_name', 'like', '%'.$value.'%')
        ->orWhere('users.surname', 'like', '%'.$value.'%')
        ->orWhere('users.second_surname', 'like', '%'.$value.'%');
    }
}
