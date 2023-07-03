<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
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

        Storage::fake();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
        ]);

        $this->user = User::factory()->create()->assignRole('admin');
    }

    public function test_can_upload_image_for_product_from_api(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('api.product.image'), [
            'image_file' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'file_name']);
    }
}
