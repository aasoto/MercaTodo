<?php

namespace App\Http\Exports;

use App\Domain\User\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class UsersReport implements FromQuery
{
    use Exportable;

    public function __construct(
        private array $filters,
    )
    {}

    public function query()
    {
        return User::query()
        -> whereSearch(isset($this->filters['search']) ? $this->filters['search'] : null)
        -> select(
            'users.id',
            'users.type_document',
            'users.number_document',
            'users.first_name',
            'users.second_name',
            'users.surname',
            'users.second_surname',
            'users.email_verified_at',
            'users.enabled',
            'states.name as state_name',
            'cities.name as city_name',
            'model_has_roles.role_id',
            'users.created_at',
        )
        -> join('states', 'users.state_id', 'states.id')
        -> join('cities', 'users.city_id', 'cities.id')
        -> join('type_documents', 'users.type_document', 'type_documents.code')
        -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
        -> orderBy('users.id');
    }
}
