<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UnitControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);
        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        $user = User::factory()->create()->assignRole('admin');

        Sanctum::actingAs($user);
    }

    public function test_can_list_products_units_from_api(): void
    {
        /**
         * @var Unit $unit
         */
        $unit = Unit::factory()->create();

        $response = $this->getJson(route('api.product.unit.index'));

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', $unit->id)
                ->where('code', $unit->code)
                ->where('name', $unit->name)
            )
            ->has('links')
            ->has('meta')
        );
    }

    public function test_can_save_product_unit_from_api(): void
    {
        /**
         * @var Unit $unit
         */
        $unit = Unit::factory()->make();

        $data = [
            'code' => $unit->code,
            'name' => $unit->name,
        ];

        $response = $this->postJson(route('api.product.unit.store'), $data);

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'message.created')
            ->has('data', fn (AssertableJson $json) =>
                $json->where('code', $data['code'])
                    ->where('name', $data['name'])
                    ->etc()
            )
        );
    }

    public function test_can_show_product_unit_from_api(): void
    {
        /**
         * @var Unit $unit
         */
        $unit = Unit::factory()->create();

        $response = $this->getJson(route('api.product.unit.show', $unit->code));

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', fn (AssertableJson $json) =>
                $json->where('id', $unit->id)
                    ->where('name', $unit->name)
                    ->where('code', $unit->code)
            )
        );
    }

    public function test_can_update_product_unit_from_api(): void
    {
        /**
         * @var Unit $unit
         */
        $unit = Unit::factory()->create();

        $response = $this->patchJson(route('api.product.unit.update', $unit->id), [
            'name' => 'changed unit',
        ]);

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'message.updated')
                ->has('data', fn (AssertableJson $json) =>
                $json->where('id', $unit->id)
                    ->where('code', $unit->code)
                    ->where('name', 'changed unit')
            )
        );
    }
}
