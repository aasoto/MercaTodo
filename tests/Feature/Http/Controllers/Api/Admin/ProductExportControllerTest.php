<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\User\Models\User;
use App\Http\Jobs\ProductExportJob;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductExportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

        $this->user = User::factory()->create()->assignRole('admin');
    }

    public function test_can_enqueue_products_export_job_from_api(): void
    {
        Sanctum::actingAs($this->user);
        Queue::fake();

        $response = $this->getJson(route('api.product.export'));

        $response
            ->assertOk()
            ->assertJsonStructure(['message']);

        Queue::assertPushed(ProductExportJob::class);
    }
}
