<?php

namespace Tests\Feature\Http\Jobs;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Http\Jobs\ProductExportJob;
use App\Http\Mail\SendEmailExportProducts;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductExportJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake(config()->get('filesystem.default'));
        Mail::fake();

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        ProductCategory::factory()->create();
        Unit::factory()->create();

        Product::factory()->count(4)->create();
    }

    public function test_can_generate_export_file_successfully(): void
    {
        $uuid = Str::uuid()->serialize();

        (new ProductExportJob(
            User::factory()->create()->assignRole('admin'),
            $uuid)
        )->handle();

        Storage::disk(config()->get('filesystem.default'))->assertExists('exports/'.$uuid.'.csv');
        Mail::assertSent(SendEmailExportProducts::class);
    }
}
