<?php

namespace App\Domain\User\Actions;

use App\Domain\User\Dtos\StoreRegisterData;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreRegisterAction
{
    public function handle(StoreRegisterData $data, ?string $role = 'client'): User
    {
        /** @phpstan-ignore-next-line */
        return User::create([
            "type_document"     => $data->type_document,
            "number_document"   => $data->number_document,
            "first_name"        => $data->first_name,
            "second_name"       => $data->second_name,
            "surname"           => $data->surname,
            "second_surname"    => $data->second_surname,
            "email"             => $data->email,
            "password"          => Hash::make($data->password),
            "birthdate"         => $data->birthdate,
            "gender"            => $data->gender,
            "phone"             => $data->phone,
            "address"           => $data->address,
            "state_id"          => $data->state_id,
            "city_id"           => $data->city_id,
        ])->assignRole($role);
    }
}
