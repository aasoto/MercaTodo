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
                    'states.name as state_name',
                    'cities.name as city_name',
                    'model_has_roles.role_id'
                )
            -> join('states', 'users.state_id', 'states.id')
            -> join('cities', 'users.city_id', 'cities.id')
            -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            -> get();

        $roles = Role::select('id', 'name')->get();
        dd($users);
        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/Index')
                -> has('users', count($users))
                -> has('roles', count($roles))
        );

    }
}
