<?php

namespace Tests\Feature\Domain\User\Traits;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Domain\User\Traits\AuthHasRole;
use Database\Seeders\RoleSeeder;
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
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
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
