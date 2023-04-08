<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_users(): void
    {
        $this->seed();

        $user = User::factory()->create()->assignRole('admin');

        $roles = Role::select('id', 'name')->get();
        Cache::put('roles', $roles);

        $response = $this->actingAs($user)
            ->get(route('user.index', "admin"));

        $roles = Role::select('id', 'name')->get();

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/Index')
                -> has('users')
                -> has('roles', count($roles))
        );

    }

    public function test_user_can_be_edited(): void
    {
        $this->seed();

        $user = User::factory()->create()->assignRole('admin');

        $response = $this->actingAs($user)
        ->get(route('user.edit', $user));

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/Edit')
                -> has('user', fn (Assert $page) => $page
                    -> where('id', $user->id)
                    -> where('first_name', $user->first_name)
                    -> where('second_name', $user->second_name)
                    -> where('surname', $user->surname)
                    -> where('second_surname', $user->second_surname)
                    -> where('email', $user->email)
                    -> where('birthdate', $user->birthdate)
                    -> where('gender', $user->gender)
                    -> where('phone', $user->phone)
                    -> where('address', $user->address)
                    -> where('state_id', $user->state_id)
                    -> where('city_id', $user->city_id)
                    -> etc()
                )
        );
    }

    public function test_user_can_be_updated(): void
    {
        $this->seed();

        $user = User::factory()->create()->assignRole('admin');
        $role = Role::select('id')->first();
        $type = TypeDocument::select('code')->inRandomOrder()->first();

        $first_name = fake()->firstName($gender = 'male'|'female');
        $address = fake()->streetAddress();

        $response = $this->actingAs($user)->patch(route('user.update', $user->id), [
            'type_document' => $type['code'],
            'number_document' => strval(fake()->randomNumber(5, true)),
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
            'role_id' => $role['id'],
            "enabled" => true
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('user.edit', $user->id));

        $user->refresh();

        $this->assertSame($first_name, $user->first_name);
        $this->assertSame($address, $user->address);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_new_user_can_be_created(): void
    {
        $this->seed();

        $user = User::factory()->create()->assignRole('admin');

        $cities = City::select('id', 'name', 'state_id')->get();
        Cache::put('cities', $cities);

        $roles = Role::select('id', 'name')->get();
        Cache::put('roles', $roles);

        $states = State::select('id', 'name')->get();
        Cache::put('states', $states);

        $type_documents = TypeDocument::select('id', 'code', 'name')->get();
        Cache::put('type_documents', $type_documents);

        $response = $this->actingAs($user)
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

        Cache::flush();
    }

    public function test_user_can_be_saved(): void
    {
        $this->seed();

        $user = User::factory()->create()->assignRole('admin');

        $state = State::select('id')->inRandomOrder()->first();
        $city = City::select('id')->where('state_id', $state["id"])->inRandomOrder()->first();
        $role = Role::select('id')->inRandomOrder()->first();
        $type = TypeDocument::select('code')->inRandomOrder()->first();

        $response = $this->actingAs($user)
        ->post(route('user.store'), [
            'type_document' => $type['code'],
            'number_document' => strval(fake()->randomNumber(5, true)),
            'first_name' => fake()->firstName($gender = 'male'|'female'),
            'second_name' => fake()->firstName($gender = 'male'|'female'),
            'surname' => fake()->lastName(),
            'second_Surname' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'birthdate' => '1989-12-04',
            'gender' => fake()->randomElement(['m', 'f', 'o']),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'state_id' => $state["id"],
            'city_id' => $city["id"],
            'role_id' => $role["id"]
        ]);

        $response
            -> assertSessionHasNoErrors()
            -> assertRedirect(route('user.index', $role['id']));
    }
}
