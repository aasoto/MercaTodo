<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        $user = User::factory()->make();

        $this->data = [
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
            'state_id' => $user->state_id,
            'city_id' => $user->city_id,
        ];
    }

    public function test_can_register_new_user_admin_by_api(): void
    {
        $response = $this->postJson(route('api.register', 'admin'), $this->data);

        $response->assertJsonMissingValidationErrors();
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message' => trans('message.created'),
            'user' => [
                'first_name' => $this->data['first_name'],
                'second_name' => $this->data['second_name'],
                'surname' => $this->data['surname'],
                'second_surname' => $this->data['second_surname'],
                'email' => $this->data['email'],
            ],
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'first_name' => $this->data['first_name'],
            'second_name' => $this->data['second_name'],
            'surname' => $this->data['surname'],
            'second_surname' => $this->data['second_surname'],
            'email' => $this->data['email'],
        ]);

        $this->assertDatabaseCount('model_has_roles', 1);
    }

    public function test_can_register_new_user_client_by_api(): void
    {
        $response = $this->postJson(route('api.register', 'client'), $this->data);

        $response->assertJsonMissingValidationErrors();
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message' => trans('message.created'),
            'user' => [
                'first_name' => $this->data['first_name'],
                'second_name' => $this->data['second_name'],
                'surname' => $this->data['surname'],
                'second_surname' => $this->data['second_surname'],
                'email' => $this->data['email'],
            ],
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'first_name' => $this->data['first_name'],
            'second_name' => $this->data['second_name'],
            'surname' => $this->data['surname'],
            'second_surname' => $this->data['second_surname'],
            'email' => $this->data['email'],
        ]);

        $this->assertDatabaseCount('model_has_roles', 1);
    }

    public function test_can_validate_when_some_fields_are_not_sent(): void
    {
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

        $response = $this->post(route('api.register', 'admin'), $data, [
            'Accept' => 'application/json',
        ]);

        $response->assertJsonValidationErrors(['number_document', 'email', 'phone', 'state_id', 'city_id']);
        $response->assertStatus(422);
    }
}
