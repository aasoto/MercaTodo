<?php

namespace App\Actions\Register;

use App\Http\Requests\Auth\RegisteredUser\StoreRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreRegisterAction
{
    public function handle(StoreRequest $request): User
    {
        $data = $request->validated();

        // Call to an undefined method Illuminate\Database\Eloquent\Model::assignRole().
        /** @phpstan-ignore-next-line */
        return User::create([
            'type_document' => $data['typeDocument'],
            'number_document' => $data['numberDocument'],
            'first_name' => $data['firstName'],
            'second_name' => $data['secondName'],
            'surname' => $data['surname'],
            'second_surname' => $data['secondSurname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'birthdate' => $data['birthdate'],
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'state_id' => $data['state'],
            'city_id' => $data['city']
        ])->assignRole('client');
    }
}
