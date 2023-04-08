<?php

namespace Tests\Feature\Auth;

use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->seed();

        $state = State::select('id')->inRandomOrder()->first();
        $city = City::select('id')->where('state_id', $state["id"])->inRandomOrder()->first();
        $type = TypeDocument::select('code')->inRandomOrder()->first();

        $response = $this->post('/register', [
            'typeDocument' => $type['code'],
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
            'state' => $state["id"],
            'city' => $city["id"],
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
