<?php

namespace Tests\Feature\Support\Http\Middleware;

use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EnsureUserHasRoleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

        $this->user = User::factory()->create()->assignRole('client');

    }

    public function test_redirect_to_required_page_if_the_user_has_the_permission_needed(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('showcase.index'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page -> component('Showcase/Index')
        );
    }

    public function test_redirect_to_page_405_when_user_has_no_permission_to_access_the_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('products.index'));

        $response->assertRedirect(route('405'));
    }
}
