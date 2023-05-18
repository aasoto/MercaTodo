<?php

namespace Tests\Feature\Controllers\Auth;

use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailVerificationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp (): void
    {
        parent::setUp();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
            UserSeeder::class,
        ]);
    }

    public function test_when_user_email_is_verified_redirect_start(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->post(route('verification.send', $user));

        $response->assertRedirect('/start');
    }

    public function test_when_user_email_is_not_verified_send_notification(): void
    {
        $user = User::factory()->create(['email_verified_at' => null])->assignRole('admin');

        $response = $this->actingAs($user)->post(route('verification.send', $user));

        $response->assertRedirect('/')
            ->assertSessionHasAll(['status' => 'verification-link-sent']);
    }
}
