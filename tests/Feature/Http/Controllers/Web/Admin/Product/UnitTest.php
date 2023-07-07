<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
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

class UnitTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Unit $unit;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        Unit::factory()->create();

        $this->user = User::factory()->create()->assignRole('admin');
        $this->unit = Unit::factory()->create();
    }

    public function test_can_list_units(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('unit.index'));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Product/Unit/Index')
                -> has('units')
                -> has('units.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('code')
                    -> has('name')
                )
        );
    }

    public function test_can_create_new_unit(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('unit.create'));

        $response->assertInertia( fn (Assert $page) => $page -> component('Product/Unit/Create'));
    }

    public function test_can_save_new_unit(): void
    {
        $code = fake()->lexify('???');

        $response = $this->actingAs($this->user)
        ->post(route('unit.store'), [
            'code' => $code,
            'name' => fake()->words(2, true),
        ]);

        $this->assertDatabaseHas('units', ['code' => $code]);

        $response->assertRedirect(route('unit.index'))
        ->assertSessionHasAll(['success' => 'Unit created.']);
    }

    public function test_can_edit_unit(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('unit.edit', $this->unit->id));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Product/Unit/Edit')
                -> has('unit', fn (Assert $page) => $page
                    -> where('code', $this->unit->code)
                    -> where('name', $this->unit->name)
                    -> etc()
                )
        );
    }

    public function test_can_update_unit(): void
    {
        $name = fake()->words(2, true);

        $response = $this->actingAs($this->user)
        ->patch(route('unit.update', $this->unit->id), [
            'name' => $name,
        ]);

        $response->assertFound();

        $this->unit->refresh();
        $this->assertSame($name, $this->unit->name);
        $this->assertDatabaseHas('units', [
            'name' => $name,
        ]);

        $response->assertRedirect(route('unit.index'))
        ->assertSessionHasAll(['success' => 'Unit updated.']);
    }
}
