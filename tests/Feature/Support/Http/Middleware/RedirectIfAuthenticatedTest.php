<?php

namespace Tests\Feature\Support\Http\Middleware;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Support\Providers\RouteServiceProvider;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RedirectIfAuthenticatedTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
    }

    public function test_redirect_to_login_page_when_a_session_is_not_active(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
    }

    public function test_redirect_to_start_page_instead_login_page_when_the_a_session_is_active(): void
    {
        $user = User::factory()->create()->assignRole('client');

        $response = $this->actingAs($user)
            ->get(route('login'));

        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
