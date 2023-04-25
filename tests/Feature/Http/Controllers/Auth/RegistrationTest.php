<?php

namespace Tests\Feature\Auth;

use App\Models\City;
use App\Providers\RouteServiceProvider;
use App\Traits\useCache;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, useCache;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

        $state = $this->getStates();
        $city = City::select('id')->where('state_id', $state[0]["id"])->inRandomOrder()->first();
        $type = $this->getTypeDocument();

        $response = $this->post('/register', [
            'typeDocument' => $type[0]['code'],
            'numberDocument' => strval(fake()->randomNumber(5, true)),
            'firstName' => fake()->firstName($gender = 'male'|'female'),
            'secondName' => fake()->firstName($gender = 'male'|'female'),
            'surname' => fake()->lastName(),
            'secondSurname' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'birthdate' => '1989-12-04',
            'gender' => fake()->randomElement(['m', 'f', 'o']),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'state' => $state[0]["id"],
            'city' => $city["id"],
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
