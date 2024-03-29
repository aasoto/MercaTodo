<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Http\Jobs\ProductImportJob;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductImportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake(config()->get('filesystem.default'));

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        $this->user = User::factory()->create()->assignRole('admin');
    }

    public function test_can_enqueue_products_import_job_from_api(): void
    {
        Sanctum::actingAs($this->user);
        Queue::fake();

        $response = $this->post(route('api.product.import'), [
            'products_file' => new UploadedFile(__DIR__.'/../../../../../Support/import-file.csv', 'testing_file', test: true),
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'file']);

        Queue::assertPushed(ProductImportJob::class);
    }
}
