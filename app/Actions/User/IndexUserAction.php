<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexUserAction
{
    public function handle(Request $request, string $role): LengthAwarePaginator
    {
        return User::query()
        -> whereSearch($request->input('search'))
        -> whenEnabled($request->input('enabled'))
        -> select(
                'users.id',
                'users.number_document',
                'users.first_name',
                'users.second_name',
                'users.surname',
                'users.second_surname',
                'users.email',
                'users.email_verified_at',
                'users.enabled',
                'states.name as state_name',
                'cities.name as city_name',
                'model_has_roles.role_id'
            )
        -> join('states', 'users.state_id', 'states.id')
        -> join('cities', 'users.city_id', 'cities.id')
        -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
        -> orderBy('users.id')
        -> role($role)
        -> paginate(10)
        -> withQueryString();
    }
}
