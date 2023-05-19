<?php

namespace Tests\Feature;

use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private TypeDocument $type;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

        $this->user = User::factory()->create();
        $this->type = TypeDocument::select('code')->inRandomOrder()->first();
    }

    public function test_profile_page_is_displayed(): void
    {

        $response = $this
            ->actingAs($this->user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $first_name = fake()->firstName($gender = 'male'|'female');
        $address = fake()->streetAddress();

        $response = $this
            ->actingAs($this->user)
            ->patch('/profile', [
                'type_document' => $this->type['code'],
                'number_document' => strval(fake()->randomNumber(5, true)),
                'first_name' => $first_name,
                'second_name' => $this->user->second_name,
                'surname' => $this->user->surname,
                'second_surname' => $this->user->second_surname,
                'email' => $this->user->email,
                'birthdate' => $this->user->birthdate,
                'gender' => $this->user->gender,
                'phone' => $this->user->phone,
                'address' => $address,
                'state_id' => $this->user->state_id,
                'city_id' => $this->user->city_id,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->user->refresh();

        $this->assertSame($first_name, $this->user->first_name);
        $this->assertSame($address, $this->user->address);
        $this->assertNotNull($this->user->email_verified_at);
    }

    public function test_email_verification_status_do_not_change_when_the_email_address_has_not_been_changed(): void
    {

        $response = $this
            ->actingAs($this->user)
            ->patch('/profile', [
                'type_document' => $this->type['code'],
                'number_document' => strval(fake()->randomNumber(5, true)),
                'first_name' => 'Test User',
                'second_name' => $this->user->second_name,
                'surname' => $this->user->surname,
                'second_surname' => $this->user->second_surname,
                'email' => $this->user->email,
                'birthdate' => $this->user->birthdate,
                'gender' => $this->user->gender,
                'phone' => $this->user->phone,
                'address' => $this->user->address,
                'state_id' => $this->user->state_id,
                'city_id' => $this->user->city_id,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($this->user->refresh()->email_verified_at);
    }

    public function test_email_verification_status_changes_when_the_email_address_has_been_changed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patch('/profile', [
                'type_document' => $this->user->type_document,
                'number_document' => $this->user->number_document,
                'first_name' => $this->user->first_name,
                'second_name' => $this->user->second_name,
                'surname' => $this->user->surname,
                'second_surname' => $this->user->second_surname,
                'email' => 'changed@example.com',
                'birthdate' => $this->user->birthdate,
                'gender' => $this->user->gender,
                'phone' => $this->user->phone,
                'address' => $this->user->address,
                'state_id' => $this->user->state_id,
                'city_id' => $this->user->city_id,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNull($this->user->refresh()->email_verified_at);
    }


    public function test_user_can_delete_their_account(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete('/profile', [
                'password' => '12345678',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($this->user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/profile');

        $this->assertNotNull($this->user->fresh());
    }
}
