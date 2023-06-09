<?php

namespace App\Domain\User\Actions;

use App\Domain\User\Dtos\UpdateUserData;
use App\Domain\User\Models\User;
use App\Domain\User\Services\RolesServices;

class UpdateUserAction
{
    public function __construct(
        protected RolesServices $roles,
    )
    {}

    public function handle(UpdateUserData $data, string $id): int
    {
        $this->roles->update($id, $data->role_id);

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
