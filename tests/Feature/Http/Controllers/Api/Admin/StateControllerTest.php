<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\User\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_list_of_states(): void
    {
        /**
         * @var State $state
         */
        $state = State::factory()->create();

        $response = $this->getJson(route('api.state.index'));

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', $state->id)
                    ->where('name', $state->name)
                    ->etc()
                )
        );
    }
}
