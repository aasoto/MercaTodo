<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_dashboard_page(): void
    {
        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

        $user = User::factory()->create()->assignRole('admin');

        $response = $this->actingAs($user)
            ->get(route('dashboard.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->has('userRole')
        );

        $response->assertView('h2')->contains('Dashboard');
    }
}
