<?php

namespace App\Actions\User;

use App\Dtos\User\UpdateUserData;
use App\Models\User;
use App\Services\User\RolesServices;

class UpdateUserAction
{
    public function handle(UpdateUserData $data, RolesServices $roles, string $id): int
    {
        $roles->update($id, $data->role_id);

        return User::where('id', $id)->update([
            'type_document'     => $data->type_document,
            'number_document'   => $data->number_document,
            'first_name'        => $data->first_name,
            'second_name'       => $data->second_name,
            'surname'           => $data->surname,
            'second_surname'    => $data->second_surname,
            'birthdate'         => $data->birthdate,
            'gender'            => $data->gender,
            'phone'             => $data->phone,
            'address'           => $data->address,
            'state_id'          => $data->state_id,
            'city_id'           => $data->city_id,
            'enabled'           => $data->enabled,
        ]);
    }
}
