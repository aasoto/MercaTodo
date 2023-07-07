<?php

namespace Tests\Feature\Http\Controllers\Web\Auth;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EmailVerificationPromptTest extends TestCase
{
    use RefreshDatabase;

    public function setUp (): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        User::factory()->create()->assignRole('admin');
    }

    public function test_if_email_is_verfied_direct_start(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertRedirect('/start');

    }

    public function test_if_email_is_not_verfied_direct_verify_email_page(): void
    {
        $user = User::factory()->create(['email_verified_at' => null])->assignRole('admin');

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertInertia(fn (Assert $page) => $page
                ->component('Auth/VerifyEmail')
                ->has('status')
        );
    }
}
