<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\User;

use App\Domain\User\Models\City;
use App\Domain\User\Models\ModelHasRole as Role;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        $this->user = User::factory()->create()->assignRole('admin');
    }

    public function test_can_list_users(): void
    {

        $response = $this->actingAs($this->user)
            ->get(route('user.index', "admin"));

        $roles = Role::getFromCache();

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/Index')
                -> has('users')
                -> has('users.data.0', fn (Assert $page) => $page
                    -> has('number_document')
                    -> has('first_name')
                    -> has('second_name')
                    -> has('surname')
                    -> has('second_surname')
                    -> has('email')
                    -> etc()
                )
                -> has('roles', count($roles))
        );

    }

    public function test_user_can_be_search_by_email(): void
    {
        User::factory()->create([
            'email' => 'music@music.com'
        ])->assignRole('admin');

        $response = $this->actingAs($this->user)
            ->get('/user/admin?search=music@music.com');

        $response->assertStatus(200);

        $response->assertSee('music@music.com');
    }

    public function test_can_list_only_enabled_users(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/user/admin?enabled=true');

        $response->assertStatus(200);

        $response->assertSee($this->user->email);
    }

    public function test_can_list_only_disabled_users(): void
    {
        $searching_user = User::factory()->create([
            'enabled' => false
        ])->assignRole('admin');

        $response = $this->actingAs($this->user)
            ->get('/user/admin?enabled=false');

        $response->assertStatus(200);

        $response->assertSee($searching_user->email);
    }

    public function test_user_can_be_edited(): void
    {

        $response = $this->actingAs($this->user)
        ->get(route('user.edit', $this->user));

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/Edit')
                -> has('user', fn (Assert $page) => $page
                    -> where('id', $this->user->id)
                    -> where('first_name', $this->user->first_name)
                    -> where('second_name', $this->user->second_name)
                    -> where('surname', $this->user->surname)
                    -> where('second_surname', $this->user->second_surname)
                    -> where('email', $this->user->email)
                    -> where('birthdate', $this->user->birthdate)
                    -> where('gender', $this->user->gender)
                    -> where('phone', $this->user->phone)
                    -> where('address', $this->user->address)
                    -> where('state_id', $this->user->state_id)
                    -> where('city_id', $this->user->city_id)
                    -> etc()
                )
        );
    }

    public function test_user_can_be_updated(): void
    {
        $role = Role::getFromCache();
        $type = TypeDocument::getFromCache();

        $first_name = fake()->firstName($gender = 'male'|'female');
        $address = fake()->streetAddress();

        $response = $this->actingAs($this->user)->patch(route('user.update', $this->user->id), [
            'type_document' => $type[0]['code'],
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
            'role_id' => $role[0]['id'],
            "enabled" => true
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('user.edit', $this->user->id));

        $this->user->refresh();

        $this->assertSame($first_name, $this->user->first_name);
        $this->assertSame($address, $this->user->address);
        $this->assertNotNull($this->user->email_verified_at);
    }

    public function test_new_user_can_be_created(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('user.create'));

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/Create')
                -> has('cities.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('name')
                    -> has('state_id')
                )
                -> has('roles.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('name')
                )
                -> has('states.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('name')
                )
                -> has('typeDocuments.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('code')
                    -> has('name')
                )
        );

    }

    public function test_user_can_be_saved(): void
    {
        $state = State::getFromCache();
        $city = City::select('id')->where('state_id', $state[0]["id"])->inRandomOrder()->first();
        $role = Role::getFromCache();
        $type = TypeDocument::getFromCache();
        $email = fake()->safeEmail();

        $response = $this->actingAs($this->user)
        ->post(route('user.store'), [
            'type_document' => $type[0]['code'],
            'number_document' => strval(fake()->randomNumber(5, true)),
            'first_name' => fake()->firstName($gender = 'male'|'female'),
            'second_name' => fake()->firstName($gender = 'male'|'female'),
            'surname' => fake()->lastName(),
            'second_surname' => fake()->lastName(),
            'email' => $email,
            'birthdate' => '1989-12-04',
            'gender' => fake()->randomElement(['m', 'f', 'o']),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'state_id' => $state[0]["id"],
            'city_id' => $city["id"],
            'role_id' => $role[0]["id"]
        ]);

        $this->assertDatabaseHas('users', ['email' => $email]);

        $response
            -> assertSessionHasNoErrors()
            -> assertRedirect(route('user.index', $role[0]['name']));
    }
}
