<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

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

class StateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private State $state;

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
    }

    public function test_can_list_states(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('state.index'));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/State/Index')
                -> has('states')
                -> has('states.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('name')
                )
        );
    }

    public function test_can_show_form_create_state(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('state.create'));

        $response->assertInertia( fn (Assert $page) => $page -> component('User/State/Create'));
    }

    public function test_can_save_new_state(): void
    {
        $name = fake()->lexify('??????');

        $response = $this->actingAs($this->user)
            ->post(route('state.store'), [
                'name' => $name,
            ]);

        $this->assertDatabaseHas('states', ['name' => $name]);

        $response->assertRedirect(route('state.index'))
            ->assertSessionHasAll(['success' => 'State created.']);
    }

    public function test_can_edit_state(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('state.edit', $this->state->id));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/State/Edit')
                -> has('state', fn (Assert $page) => $page
                    -> where('name', $this->state->name)
                    -> etc()
                )
        );
    }

    public function test_can_update_state(): void
    {
        $name = fake()->words(2, true);

        $response = $this->actingAs($this->user)
        ->patch(route('state.update', $this->state->id), [
            'name' => $name,
        ]);

        $response->assertFound();

        $this->state->refresh();
        $this->assertSame($name, $this->state->name);
        $this->assertDatabaseHas('states', [
            'name' => $name,
        ]);

        $response->assertRedirect(route('state.index'))
        ->assertSessionHasAll(['success' => 'State updated.']);
    }
}
