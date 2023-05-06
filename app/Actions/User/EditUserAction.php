<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EditUserAction
{
    public function handle(string $id): Model|null
    {
        return User::select(
            'users.id',
            'users.type_document',
            'users.number_document',
            'users.first_name',
            'users.second_name',
            'users.surname',
            'users.second_surname',
            'users.email',
            'users.birthdate',
            'users.gender',
            'users.phone',
            'users.address',
            'users.enabled',
            'users.state_id',
            'users.city_id',
            'model_has_roles.role_id'
        )
        -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
        -> where('users.id', $id)
        -> first();
    }
}
