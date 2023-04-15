<?php

namespace Tests\Feature\Controllers\Dashboard;

use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_dashboard_page(): void
    {
        $this->seed();

        $user = User::factory()->create()->assignRole('admin');

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->has('userRole')
        );

        $response->assertView('h2')->contains('Dashboard');
    }
}
