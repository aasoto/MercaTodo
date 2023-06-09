<?php

namespace Tests\Feature\Http\Controllers\Web\Auth;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Support\Providers\RouteServiceProvider;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

        $state = State::getFromCache();
        $city = City::select('id')->where('state_id', $state[0]["id"])->inRandomOrder()->first();
        $type = TypeDocument::getFromCache();
        $email = fake()->safeEmail();

        $response = $this->post('/register', [
            'typeDocument' => $type[0]['code'],
            'numberDocument' => strval(fake()->randomNumber(5, true)),
            'firstName' => fake()->firstName($gender = 'male'|'female'),
            'secondName' => fake()->firstName($gender = 'male'|'female'),
            'surname' => fake()->lastName(),
            'secondSurname' => fake()->lastName(),
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'birthdate' => '1989-12-04',
            'gender' => fake()->randomElement(['m', 'f', 'o']),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'state' => $state[0]["id"],
            'city' => $city["id"],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
