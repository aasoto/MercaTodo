<?php

namespace App\Domain\User\Actions;

use App\Domain\User\Dtos\StoreUserData;
use App\Domain\User\Models\User;
use App\Domain\User\Services\RolesServices;
use App\Http\Jobs\SendEmailVerificationJob;
use Illuminate\Support\Facades\Hash;

class StoreUserAction
{
    public function __construct(
        protected RolesServices $roles,
    )
    {}

    public function handle(StoreUserData $data): string
    {
        $role = $this->roles->get($data->role_id);

        // Call to an undefined method Illuminate\Database\Eloquent\Model::assignRole().
        /** @phpstan-ignore-next-line */
        $user = User::create([
            "type_document"     => $data->type_document,
            "number_document"   => $data->number_document,
            "first_name"        => $data->first_name,
            "second_name"       => $data->second_name,
            "surname"           => $data->surname,
            "second_surname"    => $data->second_surname,
            "email"             => $data->email,
            "password"          => Hash::make($data->number_document),
            "birthdate"         => $data->birthdate,
            "gender"            => $data->gender,
            "phone"             => $data->phone,
            "address"           => $data->address,
            "state_id"          => $data->state_id,
            "city_id"           => $data->city_id
        ])
            -> assignRole($role ? $role['name'] : '');

        SendEmailVerificationJob::dispatch($user)->onQueue('email_verification');

        return $role ? $role['name'] : '';
    }
}
