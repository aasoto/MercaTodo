<?php

namespace Tests\Feature\Domain\Product\Traits;

use App\Domain\Product\Traits\StorageFiles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreageFilesTest extends TestCase
{
    use RefreshDatabase, StorageFiles;

    private UploadedFile $file;
    private string $path;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->file = UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500);
        $this->path = 'images/products';
    }

    public function test_can_upload_file(): void
    {
        $response = $this->upload($this->file, $this->path, 1);

        $this->assertIsString($response);
        $this->assertStringEndsWith('.png', $response);

        Storage::disk('public')->assertExists($this->path.'/'.$response);
    }

    public function test_can_remove_file(): void
    {
        $uploaded_file = $this->upload($this->file, $this->path, 1);

        Storage::disk('public')->assertExists($this->path.'/'.$uploaded_file);

        $this->remove($this->path, $uploaded_file);

        Storage::disk('public')->assertMissing($this->path.'/'.$uploaded_file);
    }
}
