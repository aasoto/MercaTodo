<?php

namespace Tests\Feature\Support\Http\Middleware;

use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserIsEnabledTest extends TestCase
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

    public function test_show_required_page_when_the_user_is_enabled(): void
    {
        $user = User::factory()->create()->assignRole('admin');

        $response = $this->actingAs($user)
            ->get(route('products.index'));

        $response->assertOk();
    }

    public function test_redirect_to_disabled_page_when_the_user_is_disabled(): void
    {
        $user = User::factory()->create([
            'enabled' => false,
        ])->assignRole('admin');

        $response = $this->actingAs($user)
            ->get(route('products.index'));

        $response->assertRedirect(route('user-disabled'));
    }
}
