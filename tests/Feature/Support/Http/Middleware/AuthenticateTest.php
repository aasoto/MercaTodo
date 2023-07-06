<?php

namespace Tests\Feature\Support\Http\Middleware;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_redirect_when_user_has_been_authenticated(): void
    {
        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ])->assignRole('admin');

        $response = $this->actingAs($user)
        ->get(route('verification.notice'));

        $response->assertOk();
    }

    public function test_redirect_to_login_when_user_is_not_authenticated(): void
    {
        $response = $this->get(route('verification.notice'));

        $response->assertRedirect(route('login'));
    }
}
