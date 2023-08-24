<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_revoke_all_users_tokens(): void
    {
        TypeDocument::factory()->create();
        State::factory()->create();
        City::factory()->create();

        $this->seed([
            RoleSeeder::class,
        ]);
        /**
         * @var User $user
         */
        $user = User::factory()->create()->assignRole('admin');

        $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->postJson(route('api.logout'), [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);

    }
}
