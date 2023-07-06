<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Http\Jobs\ProductExportJob;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProductExportTest extends TestCase
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

    public function test_can_validate_that_route_redirect_products_index(): void
    {
        $this->actingAs($this->user)->get(route('product.export'))
        ->assertRedirect()
        ->assertRedirect(route('products.index'))
        ->assertSessionHasAll(['success' => 'Products exported.']);
    }

    public function test_can_enqueue_products_export_job(): void
    {
        Queue::fake();

        $this->actingAs($this->user)->get(route('product.export'))
        ->assertRedirect()
        ->assertRedirect(route('products.index'))
        ->assertSessionHasAll(['success' => 'Products exported.']);

        Queue::assertPushed(ProductExportJob::class);
    }
}
