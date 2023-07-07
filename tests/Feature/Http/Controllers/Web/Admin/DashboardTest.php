<?php

namespace Tests\Feature\Http\Controllers\Web\Admin;

use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\OrdersSeeder;
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
            OrdersSeeder::class,
        ]);

        $user = User::factory()->create()->assignRole('admin');

        $response = $this->actingAs($user)
            ->get(route('dashboard.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->has('userRole')
                ->has('ordersByDay')
                ->has('ordersByDay', fn (Assert $page) => $page
                    ->has('color')
                    ->has('data')
                    ->has('labels')
                )
                ->has('ordersByPaymentStatus')
                ->has('ordersByPaymentStatus', fn (Assert $page) => $page
                    ->has('colorBars')
                    ->has('colorBorderBars')
                    ->has('data')
                    ->has('labels')
                )
                ->has('productsByCategory')
                ->has('productsByCategory', fn (Assert $page) => $page
                    ->has('colors')
                    ->has('data')
                    ->has('labels')
                )
                ->has('productsStatusByStock')
                ->has('productsStatusByStock', fn (Assert $page) => $page
                    ->has('colors')
                    ->has('data')
                    ->has('labels')
                )
                ->has('productsByAvailability')
                ->has('productsByAvailability', fn (Assert $page) => $page
                    ->has('colors')
                    ->has('data')
                    ->has('labels')
                )
                ->etc()
            );

    }
}
