<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
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
    public function definition(): array
    {
        $state = $this->search_state();
        return [
            // 'name' => fake()->name(),
            'type_doc' => fake()->randomElement(['cc', 'pas', 'o']),
            'num_doc' => fake()->randomNumber(7, true),
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

    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function unverified(): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'email_verified_at' => null,
    //     ]);
    // }

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
