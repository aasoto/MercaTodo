<?php

namespace App\Domain\User\QueryBuilders;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @method static User select(...$parameters)
 * @method static User whereBetween(...$parameters)
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

    public function whereTypeDocument(?string $type_document): self
    {
        return $type_document ? $this->where('users.type_document', $type_document) : $this;
    }

    public function whereEmailVerified(?string $verified): self
    {
        if ($verified == 'true') {
            return $this->where('users.email_verified_at', '!=', null);
        } elseif ($verified == 'false') {
            return $this->where('users.email_verified_at', null);
        } else {
            return $this;
        }
    }

    public function whereEnabled(?string $enabled): self
    {
        if ($enabled == 'true') {
            return $this->where('users.enabled', '1');
        } elseif ($enabled == 'false') {
            return $this->where('users.enabled', '0');
        } else {
            return $this;
        }
    }

    public function whereRole(?string $role_id): self
    {
        return $role_id ? $this->where('model_has_roles.role_id', $role_id) : $this;
    }

    public function whereBetweenCreatedAt(?string $date_1, ?string $date_2): self|User
    {
        if ($date_1 && $date_2) {
            return $this->whereBetween('users.created_at', [$date_1, $date_2]);
        } elseif ($date_1) {
            return $this->where('users.created_at', 'like', '%'.$date_1.'%');
        } elseif ($date_2) {
            return $this->where('users.created_at', 'like', '%'.$date_2.'%');
        } else {
            return $this;
        }
    }

    public function whereStateAndCity(?string $state_id, ?string $city_id): self
    {
        if ($state_id && $city_id) {
            return $this->where('users.state_id', $state_id)->where('users.city_id', $city_id);
        } elseif ($state_id) {
            return $this->where('users.state_id', $state_id);
        } elseif ($city_id) {
            return $this->where('users.city_id', $city_id);
        } else {
            return $this;
        }
    }

    public function queryBuilderIndex(): QueryBuilder
    {
        return QueryBuilder::for(User::class)
        ->allowedFilters(['type_document', 'number_document', 'first_name', 'second_name', 'surname', 'second_surname', 'email', 'password', 'birthdate', 'gender', 'address', 'phone', 'state_id', 'city_id', 'enabled'])
        ->allowedIncludes(['typeDocument', 'state', 'city']);
    }
}
