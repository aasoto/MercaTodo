<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_new_user_by_api(): void
    {
        $this->seed([RoleSeeder::class]);
        $type_document = TypeDocument::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create();

        $data = [
            'type_document' => $type_document['code'],
            'number_document' => fake()->randomNumber(7, true),
            'first_name' => fake()->firstName($gender = 'male'|'female'),
            'second_name' => fake()->firstName($gender = 'male'|'female'),
            'surname' => fake()->lastName(),
            'second_surname' => fake()->lastName() ,
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'birthdate' => fake()->date(),
            'gender' => fake()->randomElement(['m', 'f', 'o']),
            'address' => fake()->streetAddress(),
            'phone' => fake()->phoneNumber(),
            'state_id' => $state["id"],
            'city_id' => $city["id"],
        ];

        $response = $this->post(route('api.register'), $data, [
            'Accept' => 'application/json',
        ]);

        $response->assertJsonMissingValidationErrors();
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message' => trans('message.created'),
            'user' => [
                'first_name' => $data['first_name'],
                'second_name' => $data['second_name'],
                'surname' => $data['surname'],
                'second_surname' => $data['second_surname'],
                'email' => $data['email'],
            ],
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'first_name' => $data['first_name'],
            'second_name' => $data['second_name'],
            'surname' => $data['surname'],
            'second_surname' => $data['second_surname'],
            'email' => $data['email'],
        ]);
    }

    public function test_can_validate_when_some_fields_are_not_sent(): void
    {
        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            TypeDocumentSeeder::class,
            RoleSeeder::class,
        ]);
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $data = [
            'type_document' => $user->type_document,
            'number_document' => $user->number_document,
            'first_name' => $user->first_name,
            'second_name' => $user->second_name,
            'surname' => $user->surname,
            'second_surname' => $user->second_surname,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            'birthdate' => $user->birthdate,
            'gender' => $user->gender,
            'address' => $user->address,
            'phone' => $user->phone,
            'state_id' => '',
            'city_id' => '',
        ];

        $response = $this->post(route('api.register'), $data, [
            'Accept' => 'application/json',
        ]);

        $response->assertJsonValidationErrors(['number_document', 'email', 'phone', 'state_id', 'city_id']);
        $response->assertStatus(422);
    }
}
