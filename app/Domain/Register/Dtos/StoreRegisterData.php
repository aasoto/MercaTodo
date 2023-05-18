<?php

namespace App\Domain\Register\Dtos;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class StoreRegisterData
{
    public function __construct(
        public string $type_document,
        public string $number_document,
        public string $first_name,
        public string|null $second_name,
        public string $surname,
        public string|null $second_surname,
        public string $email,
        public string $password,
        public Carbon $birthdate,
        public string $gender,
        public string $phone,
        public string $address,
        public int $state_id,
        public int $city_id,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            type_document: $request->input('typeDocument'),
            number_document: $request->input('numberDocument'),
            first_name: $request->input('firstName'),
            second_name: $request->input('secondName'),
            surname: $request->input('surname'),
            second_surname: $request->input('secondSurname'),
            email: $request->input('email'),
            password: $request->input('password'),
            birthdate: Carbon::make($request->input('birthdate')),
            gender: $request->input('gender'),
            phone: $request->input('phone'),
            address: $request->input('address'),
            state_id: $request->input('state'),
            city_id: $request->input('city'),
        );
    }
}
