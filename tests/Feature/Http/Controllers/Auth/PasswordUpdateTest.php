<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp (): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create();
    }

    public function test_password_can_be_updated(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => '12345678',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('new-password', $this->user->refresh()->password));
    }

    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasErrors('current_password')
            ->assertRedirect('/profile');
    }
}
