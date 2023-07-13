<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

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
use Inertia\Testing\AssertableInertia as Assert;
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

    public function test_can_render_products_import_frontend_component(): void
    {
        $response = $this->actingAs($this->user)->get(route('product.import.create'));

        $response -> assertStatus(200)
            -> assertInertia(fn (Assert $page) => $page
                -> component('Product/Import')
            );
    }

    public function test_can_enqueue_products_import_job(): void
    {
        Queue::fake();

        $this->actingAs($this->user)->post(route('product.import.store'), [
            'products_file' => new UploadedFile(__DIR__.'/../../../../../../Support/import-file.csv', 'testing_file', test: true),
        ])
        ->assertRedirect()
        ->assertRedirect(route('products.index'))
        ->assertSessionHasAll(['success' => 'Products imported.']);

        Queue::assertPushed(ProductImportJob::class);
    }
}
