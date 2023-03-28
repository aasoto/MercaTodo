<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Spatie\ModelHasRol;
use App\Models\State;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

use function PHPSTORM_META\map;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function prelim_data(): void
    {
        State::factory()->count(5)->create();
        City::factory()->count(25)->create();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'client']);
    }

    public function test_can_list_users(): void
    {
        $this->prelim_data();
        $user = User::factory()->create()->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $response = $this->get(route('user.index'));

        $users = User::query()
            -> select(
                    'users.first_name',
                    'users.second_name',
                    'users.surname',
                    'users.second_surname',
                    'users.email',
                    'users.birthdate',
                    'users.gender',
                    'users.phone',
                    'users.address',
                    'users.enabled',
                    'states.name as state_name',
                    'cities.name as city_name',
                    'model_has_roles.role_id'
                )
            -> join('states', 'users.state_id', 'states.id')
            -> join('cities', 'users.city_id', 'cities.id')
            -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            -> paginate(10);

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
        $this->prelim_data();

        $user = User::factory()->create()->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $response = $this->get(route('user.edit', $user));

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
        $this->prelim_data();

        $user = User::factory()->create()->assignRole('admin');
        $role = Role::select('id')->first();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        $first_name = fake()->firstName($gender = 'male'|'female');
        $address = fake()->streetAddress();

        $response = $this->actingAs($user)->patch(route('user.update', $user->id), [
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
}
