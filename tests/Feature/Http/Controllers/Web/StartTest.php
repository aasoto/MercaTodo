<?php

namespace Tests\Feature\Http\Controllers\Web;

use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StartTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

    }

    public function test_when_user_role_is_admin_shows_dashboard(): void
    {
        $user = User::factory()->create()->assignRole('admin');

        $response = $this->actingAs($user)
            ->get(route('start'));

        $response->assertFound();

        $response->assertRedirect(route('dashboard.index'))
            ->assertSessionHasAll(['user_role' => 'admin']);
    }

    public function test_when_user_role_is_client_shows_showcase(): void
    {
        $user = User::factory()->create()->assignRole('client');

        $response = $this->actingAs($user)
            ->get(route('start'));

        $response->assertFound();

        $response->assertRedirect(route('showcase.index'))
            ->assertSessionHasAll(['user_role' => 'client']);
    }

    public function test_when_user_role_is_wrong_dispatch_error(): void
    {
        $user = User::factory()->create()->assignRole('');

        $response = $this->actingAs($user)
            ->get(route('start'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Auth/NoRole')
        );
    }
}
