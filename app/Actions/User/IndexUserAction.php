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
        -> when($request->input('search'), function ($query, $search) {
            $query->where('users.email', 'like', '%'.$search.'%')
            ->orWhere('users.number_document', 'like', '%'.$search.'%')
            ->orWhere('users.first_name', 'like', '%'.$search.'%')
            ->orWhere('users.second_name', 'like', '%'.$search.'%')
            ->orWhere('users.surname', 'like', '%'.$search.'%')
            ->orWhere('users.second_surname', 'like', '%'.$search.'%')
            ->orWhere('cities.name', 'like', '%'.$search.'%')
            ->orWhere('states.name', 'like', '%'.$search.'%');
        })
        -> when($request->input('enabled'), function ($query, $search) {
            if ($search == 'true') {
                $query->where('users.enabled', '1');
            }
            if ($search == 'false') {
                $query->where('users.enabled', '0');
            }
        })
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
