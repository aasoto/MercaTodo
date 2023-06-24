<?php

namespace Tests\Feature\Http\Jobs;

use App\Domain\Product\Models\Product;
use App\Http\Jobs\ProductExportJob;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $this->seed([
            ProductCategorySeeder::class,
            UnitSeeder::class,
        ]);

        Product::factory()->count(4)->create();
        $uuid = Str::uuid()->serialize();
        (new ProductExportJob($uuid))->handle();
        Storage::disk(config()->get('filesystem.default'))->assertExists('exports/'.$uuid.'.csv');
    }
}
