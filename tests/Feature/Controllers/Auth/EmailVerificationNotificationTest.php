<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailVerificationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_user_email_is_verified_show_dashboard(): void
    {
        $this->seed();
        $user = User::first();

        $response = $this->actingAs($user)->post(route('verification.send', $user));

        $response->assertRedirect('/dashboard');
    }

    public function test_when_user_email_is_not_verified_send_notification(): void
    {
        $this->seed();
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->post(route('verification.send', $user));

        $response->assertRedirect('/')
            ->assertSessionHasAll(['status' => 'verification-link-sent']);
    }
}
