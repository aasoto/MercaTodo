<?php

namespace App\QueryBuilders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static User select(...$parameters)
 */
class UserQueryBuilder extends Builder
{
    public function whereSearch(?string $search): self
    {
        return $search ? $this -> where('users.email', 'like', '%'.$search.'%')
        ->orWhere('users.number_document', 'like', '%'.$search.'%')
        ->orWhere('users.first_name', 'like', '%'.$search.'%')
        ->orWhere('users.second_name', 'like', '%'.$search.'%')
        ->orWhere('users.surname', 'like', '%'.$search.'%')
        ->orWhere('users.second_surname', 'like', '%'.$search.'%')
        ->orWhere('cities.name', 'like', '%'.$search.'%')
        ->orWhere('states.name', 'like', '%'.$search.'%')
        : $this;
    }

    public function whenEnabled(?string $enabled): self
    {
        return $enabled ? $this -> when($enabled, function ($query, $search) {
            if ($search == 'true') {
                $query->where('users.enabled', '1');
            }
            if ($search == 'false') {
                $query->where('users.enabled', '0');
            }
        }) : $this;
    }
}
