<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            TypeDocumentSeeder::class,
            RoleSeeder::class,
        ]);
    }

    public function test_can_login_from_api(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => '12345678',
        ], ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonMissingValidationErrors(['email', 'password']);
        $response->assertJsonStructure([
            'access_token',
        ]);
    }

    public function test_can_validate_when_the_password_is_wrong(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => 'password_wrong',
        ], ['accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => trans('auth.failed'),
        ]);
    }

    public function test_can_validated_when_all_field_are_wrong(): void
    {
        $response = $this->post(route('api.login'), [
            'email' => 'email@',
            'password' => '',
        ], ['accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }
}
