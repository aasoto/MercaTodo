<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductUploadImageControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        ProductCategory::factory()->create();
        Unit::factory()->create();

        $this->user = User::factory()->create()->assignRole('admin');
        Sanctum::actingAs($this->user, ['admin']);

    }

    public function test_can_upload_image_for_product_from_api(): void
    {
        $response = $this->postJson(route('api.product.image'), [
            'image_file' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'file_name']);
    }

    public function test_can_replace_image_for_product_from_api(): void
    {
        /**
         * @var Product $product
         */
        $product = Product::factory()->create();

        $response = $this->postJson(route('api.product.image'), [
            'product_id' => $product->id,
            'picture_number' => rand(1, 3),
            'image_file' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'file_name']);
    }
}
