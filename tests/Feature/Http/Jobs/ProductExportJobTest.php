<?php

namespace Tests\Feature\Http\Jobs;

use App\Domain\Product\Models\Product;
use App\Domain\User\Models\User;
use App\Http\Jobs\ProductExportJob;
use App\Http\Mail\SendEmailExportProducts;
use Database\Seeders\CitySeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductExportJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_export_file_successfully(): void
    {
        Storage::fake(config()->get('filesystem.default'));
        Storage::fake('public');
        Mail::fake();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            TypeDocumentSeeder::class,
            ProductCategorySeeder::class,
            UnitSeeder::class,
            RoleSeeder::class,
        ]);

        Product::factory()->count(4)->create();

        $uuid = Str::uuid()->serialize();

        (new ProductExportJob(
            User::factory()->create()->assignRole('admin'),
            $uuid)
        )->handle();

        Storage::disk(config()->get('filesystem.default'))->assertExists('exports/'.$uuid.'.csv');
        Mail::assertSent(SendEmailExportProducts::class);
    }
}