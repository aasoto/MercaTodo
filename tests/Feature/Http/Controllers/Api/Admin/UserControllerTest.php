<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        TypeDocument::factory()->create();
        State::factory()->create();
        City::factory()->create();

        $this->seed([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        $this->user = User::first();

        Sanctum::actingAs($this->user, ['admin']);

    }

    public function test_can_list_users_from_api(): void
    {
        $response = $this->getJson(route('api.user.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 2)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', $this->user->id)
                    ->where('number_document', $this->user->number_document)
                    ->where('first_name', $this->user->first_name)
                    ->where('surname', $this->user->surname)
                    ->where('birthdate', $this->user->birthdate)
                    ->etc()
            )
            ->has('links')
            ->has('meta')
        );
    }

    public function test_can_filter_users_list_by_costumized_filter_name(): void
    {
        $response = $this->getJson('/api/v1/users?filter[name]='.$this->user->first_name);

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', $this->user->id)
                    ->where('number_document', $this->user->number_document)
                    ->where('first_name', $this->user->first_name)
                    ->where('surname', $this->user->surname)
                    ->where('birthdate', $this->user->birthdate)
                    ->etc()
            )
            ->has('links')
            ->has('meta')
        );
    }
}
