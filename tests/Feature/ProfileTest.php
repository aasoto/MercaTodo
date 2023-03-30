<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function prelim_data(): void
    {
        State::factory()->count(5)->create();
        City::factory()->count(25)->create();
        Role::create(['name' => 'client']);
    }

    public function test_profile_page_is_displayed(): void
    {
        $this->prelim_data();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $this->prelim_data();

        $user = User::factory()->create();

        $first_name = fake()->firstName($gender = 'male'|'female');
        $address = fake()->streetAddress();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'type_doc' => fake()->randomElement(['cc', 'pas', 'o']),
                'num_doc' => strval(fake()->randomNumber(5, true)),
                'first_name' => $first_name,
                'second_name' => $user->second_name,
                'surname' => $user->surname,
                'second_surname' => $user->second_surname,
                'email' => $user->email,
                'birthdate' => $user->birthdate,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'address' => $address,
                'state_id' => $user->state_id,
                'city_id' => $user->city_id,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame($first_name, $user->first_name);
        $this->assertSame($address, $user->address);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $this->prelim_data();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'type_doc' => fake()->randomElement(['cc', 'pas', 'o']),
                'num_doc' => strval(fake()->randomNumber(5, true)),
                'first_name' => 'Test User',
                'second_name' => $user->second_name,
                'surname' => $user->surname,
                'second_surname' => $user->second_surname,
                'email' => $user->email,
                'birthdate' => $user->birthdate,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'address' => $user->address,
                'state_id' => $user->state_id,
                'city_id' => $user->city_id,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $this->prelim_data();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => '12345678',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $this->prelim_data();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
