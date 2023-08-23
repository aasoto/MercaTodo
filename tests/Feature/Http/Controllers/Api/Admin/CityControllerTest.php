<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use Database\Seeders\CitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_list_of_cities(): void
    {
        State::factory()->create();

        /**
         * @var City $city
         */
        $city = City::factory()->create();

        $response = $this->getJson(route('api.city.index'));

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
        $json->has('data', 1)
        ->has('data.0', fn (AssertableJson $json) =>
            $json->where('id', $city->id)
                ->where('name', $city->name)
                ->where('state_id', $city->state_id)
                ->etc()
            )
        );
    }
}
