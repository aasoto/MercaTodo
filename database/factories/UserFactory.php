<?php

namespace Database\Factories;

use App\Domain\TypeDocument\Models\TypeDocument;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;

    public function definition(): array
    {
        $state = $this->search_state();
        return [
            'type_document' => $this->search_type_document(),
            'number_document' => fake()->randomNumber(7, true),
            'first_name' => fake()->firstName($gender = 'male'|'female'),
            'second_name' => fake()->firstName($gender = 'male'|'female'),
            'surname' => fake()->lastName(),
            'second_surname' => fake()->lastName() ,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // password
            'birthdate' => fake()->date(),
            'gender' => fake()->randomElement(['m', 'f', 'o']),
            'address' => fake()->streetAddress(),
            'phone' => fake()->phoneNumber(),
            'enabled' => true,
            'state_id' => $state["id"],
            'city_id' => $this->search_city($state["id"]),
            // 'remember_token' => Str::random(10),
        ];
    }

    public function search_type_document ()
    {
        $type = TypeDocument::select('code')->inRandomOrder()->first();
        return $type['code'];
    }

    public function search_state ()
    {
        return State::select('id')->inRandomOrder()->first();
    }

    public function search_city ($state)
    {
        $city = City::select('id')->where('state_id', $state)->inRandomOrder()->first();
        return $city["id"];
    }
}
