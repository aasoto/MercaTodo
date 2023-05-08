<?php

namespace App\Dtos\User;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class StoreUserData
{
    public function __construct(
        public string $type_document,
        public string $number_document,
        public string $first_name,
        public string|null $second_name,
        public string $surname,
        public string|null $second_surname,
        public string $email,
        public Carbon $birthdate,
        public string $gender,
        public string $phone,
        public string $address,
        public int $state_id,
        public int $city_id,
        public int $role_id,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            type_document: $request->input('type_document'),
            number_document: $request->input('number_document'),
            first_name: $request->input('first_name'),
            second_name: $request->input('second_name'),
            surname: $request->input('surname'),
            second_surname: $request->input('second_surname'),
            email: $request->input('email'),
            birthdate: Carbon::make($request->input('birthdate')),
            gender: $request->input('gender'),
            phone: $request->input('phone'),
            address: $request->input('address'),
            state_id: $request->input('state_id'),
            city_id: $request->input('city_id'),
            role_id: $request->input('role_id'),
        );
    }
}
