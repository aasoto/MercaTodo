<?php

namespace Tests\Feature\Http\Controllers\Web\Auth;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
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
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        User::factory()->create()->assignRole('admin');
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
