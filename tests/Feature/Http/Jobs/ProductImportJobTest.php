<?php

namespace Tests\Feature\Http\Jobs;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Http\Jobs\ProductImportJob;
use App\Http\Mail\SendEmailImportProducts;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductImportJobTest extends TestCase
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

    public function test_can_import_products(): void
    {
        Storage::fake(config()->get('filesystem.default'));
        Bus::fake();
        Mail::fake();
        Sanctum::actingAs($this->user);

        $response = $this->post(route('api.product.import'), [
            'products_file' => new UploadedFile(__DIR__.'/../../../Support/import-file.csv', 'testing_file', test: true),
        ]);

        Bus::assertDispatched(ProductImportJob::class, function (ProductImportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(SendEmailImportProducts::class);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'file']);

        $this->assertDatabaseCount('products', 4);
        $this->assertDatabaseHas('products', [
            'name' => 'Test import product',
            'barcode' => '123456789',
            'price' => 10000,
            'unit' => 'unidad',
        ]);

        $this->assertDatabaseCount('products_categories', 4);
        $this->assertDatabaseHas('products_categories', [
            'name' => 'Test import category',
        ]);

        $this->assertDatabaseCount('units', 3);
        $this->assertDatabaseHas('units', [
            'code' => 'unidad',
            'name' => 'Unidad',
        ]);
    }
}
