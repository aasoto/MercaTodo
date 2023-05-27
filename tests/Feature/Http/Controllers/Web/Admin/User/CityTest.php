<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\User;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private State $state;
    private City $city;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
            UnitSeeder::class,
        ]);

        $this->user = User::factory()->create()->assignRole('admin');
        $this->state = State::factory()->create();
        $this->city = City::factory()->create();
    }

    public function test_can_list_cities(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('city.index'));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/City/Index')
                -> has('cities')
                    -> has('cities.0', fn (Assert $page) => $page
                        -> has('id')
                        -> has('name')
                        -> has('state_id')
                    )
                -> has('states')
                    -> has('states.0', fn (Assert $page) => $page
                        -> has('id')
                        -> has('name')
                    )
        );
    }

    public function test_can_show_create_city_form(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('city.create'));

        $response->assertInertia( fn (Assert $page) => $page
            -> component('User/City/Create')
            -> has('states')
                -> has('states.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('name')
                )
        );
    }

    public function test_can_save_new_city(): void
    {
        $name = fake()->city();
        $state = State::select('id')->inRandomOrder()->first();
        $response = $this->actingAs($this->user)
        ->post(route('city.store'), [
            'name' => $name,
            'state_id' => intval($state['id']),
        ]);

        $this->assertDatabaseHas('cities', ['name' => $name]);

        $response->assertRedirect(route('city.index'))
        ->assertSessionHasAll(['success' => 'City created.']);
    }

    public function test_can_edit_unit(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('city.edit', $this->city->id));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/City/Edit')
                -> has('city', fn (Assert $page) => $page
                    -> where('name', $this->city->name)
                    -> where('state_id', $this->city->state_id)
                    -> etc()
                )
                -> has('states')
                -> has('states.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('name')
                )
        );
    }

    public function test_can_update_city(): void
    {
        $name = fake()->city();

        $response = $this->actingAs($this->user)
        ->patch(route('city.update', $this->city->id), [
            'name' => $name,
            'state_id' => $this->city->state_id,
        ]);

        $response->assertFound();

        $this->city->refresh();
        $this->assertSame($name, $this->city->name);
        $this->assertDatabaseHas('cities', [
            'name' => $name,
        ]);

        $response->assertRedirect(route('city.index'))
        ->assertSessionHasAll(['success' => 'City updated.']);
    }

    public function test_can_update_state_of_city(): void
    {
        $state = State::select('id')->inRandomOrder()->first();

        $response = $this->actingAs($this->user)
        ->patch(route('city.update', $this->city->id), [
            'name' => $this->city->name,
            'state_id' => intval($state['id']),
        ]);

        $response->assertFound();

        $this->city->refresh();
        $this->assertSame($state['id'], $this->city->state_id);

        $response->assertRedirect(route('city.index'))
        ->assertSessionHasAll(['success' => 'City updated.']);
    }
}
