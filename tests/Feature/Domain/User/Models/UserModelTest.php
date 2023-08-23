<?php

namespace Tests\Feature\Domain\User\Models;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    private TypeDocument $type_document;
    private State $state;
    private City $city;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->type_document = TypeDocument::factory()->create();
        $this->state = State::factory()->create();
        $this->city = City::factory()->create();

        $this->seed([
            RoleSeeder::class,
        ]);

        $this->user = User::factory()->create()->assignRole('admin');
    }

    public function test_can_access_to_type_document_of_user(): void
    {
        $this->assertEquals($this->type_document->name, $this->user->typeDocument->name);
    }

    public function test_can_access_to_state_of_user(): void
    {
        $this->assertEquals($this->state->name, $this->user->state->name);
    }

    public function test_can_access_to_city_of_user(): void
    {
        $this->assertEquals($this->city->name, $this->user->city->name);
    }

    public function test_can_access_to_state_of_city(): void
    {
        $this->assertEquals($this->state->name, $this->city->state->name);
    }
}
