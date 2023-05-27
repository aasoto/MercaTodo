<?php

namespace Tests\Feature\Domain\User\Traits;

use App\Domain\User\Models\User;
use App\Domain\User\Traits\AuthHasRole;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthHasRoleTest extends TestCase
{
    use RefreshDatabase, AuthHasRole;

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

    public function test_return_role_name_of_user(): void
    {
        $user = User::factory()->create()->assignRole('admin');
        $roles = Role::select('id', 'name')->get();
        $response = $this->actingAs($user);

        $role = $this->authHasRole($roles);
        $this->assertEquals('admin', $role);
    }

    public function test_return_empty_when_user_has_not_assigned_role(): void
    {
        $user = User::factory()->create()->assignRole('');
        $roles = Role::select('id', 'name')->get();
        $response = $this->actingAs($user);

        $role = $this->authHasRole($roles);
        $this->assertEquals('', $role);
    }
}
