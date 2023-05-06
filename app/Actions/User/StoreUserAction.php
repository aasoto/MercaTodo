<?php

namespace App\Actions\User;

use App\Http\Requests\Admin\User\StoreRequest;
use App\Models\User;
use App\Services\User\RolesServices;
use Illuminate\Support\Facades\Hash;

class StoreUserAction
{
    public function handle(StoreRequest $request, RolesServices $roles): string
    {
        $data = $request->validated();
        $role = $roles->get($data["role_id"]);

        // Call to an undefined method Illuminate\Database\Eloquent\Model::assignRole().
        /** @phpstan-ignore-next-line */
        User::create([
            "type_document" => $data["type_document"],
            "number_document" => $data["number_document"],
            "first_name" => $data["first_name"],
            "second_name" => $data["second_name"],
            "surname" => $data["surname"],
            "email" => $data["email"],
            "password" => Hash::make($data["number_document"]),
            "birthdate" => $data["birthdate"],
            "gender" => $data["gender"],
            "phone" => $data["phone"],
            "address" => $data["address"],
            "state_id" => $data["state_id"],
            "city_id" => $data["city_id"]
        ])
            -> assignRole($role ? $role['name'] : '')
            -> sendEmailVerificationNotification();

        return $role ? $role['name'] : '';
    }
}
