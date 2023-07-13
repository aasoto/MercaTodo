<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        $this->user = User::factory()->create()->assignRole('admin');
    }

    public function test_can_render_reports_frontend_component(): void
    {
        $response = $this->actingAs($this->user)->get(route('report.index'));

        $response -> assertStatus(200)
            -> assertInertia(fn (Assert $page) => $page
                -> component('Report/Index')
            );
    }
}
