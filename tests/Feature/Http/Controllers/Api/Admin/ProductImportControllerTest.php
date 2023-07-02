<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\User\Models\User;
use App\Http\Jobs\ProductImportJob;
use App\Http\Mail\SendEmailImportProducts;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
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

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

        $this->user = User::factory()->create()->assignRole('admin');
    }

    public function test_can_import_products(): void
    {
        Storage::fake();
        Bus::fake();
        Mail::fake();
        Sanctum::actingAs($this->user);

        $response = $this->post(route('api.product.import'), [
            'products_file' => new UploadedFile(__DIR__.'/../../../../../Support/import-file.csv', 'testing_file', test: true),
        ]);

        Bus::assertDispatched(ProductImportJob::class, function (ProductImportJob $job) {
            $job->handle();

            return true;
        });

        Mail::assertSent(SendEmailImportProducts::class);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'file']);

        $this->assertDatabaseCount('products', 3);
        $this->assertDatabaseHas('products', [
            'id' => 1,
            'name' => 'Test import product',
            'barcode' => '123456789',
            'price' => 10000,
            'products_category_id' => '1',
            'unit' => 'unidad',
        ]);

        $this->assertDatabaseCount('products_categories', 3);
        $this->assertDatabaseHas('products_categories', [
            'id' => 1,
            'name' => 'Test import category',
        ]);

        $this->assertDatabaseCount('units', 3);
        $this->assertDatabaseHas('units', [
            'id' => 1,
            'code' => 'unidad',
            'name' => 'Unidad',
        ]);
    }
}
