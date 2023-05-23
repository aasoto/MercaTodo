<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\User;

use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TypeDocumentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private TypeDocument $type_document;

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
        $this->type_document = TypeDocument::factory()->create();
    }

    public function test_can_list_type_documents(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('type_document.index'));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/TypeDocument/Index')
                -> has('typeDocuments')
                -> has('typeDocuments.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('code')
                    -> has('name')
                )
        );
    }

    public function test_can_create_new_type_document(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('type_document.create'));

        $response->assertInertia( fn (Assert $page) => $page -> component('User/TypeDocument/Create'));
    }

    public function test_can_save_new_type_document(): void
    {
        $code = fake()->lexify('???');

        $response = $this->actingAs($this->user)
        ->post(route('type_document.store'), [
            'code' => $code,
            'name' => fake()->words(2, true),
        ]);

        $this->assertDatabaseHas('type_documents', ['code' => $code]);

        $response->assertRedirect(route('type_document.index'))
        ->assertSessionHasAll(['success' => 'Type document created.']);
    }

    public function test_can_edit_type_document(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('type_document.edit', $this->type_document->id));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('User/TypeDocument/Edit')
                -> has('typeDocument', fn (Assert $page) => $page
                    -> where('code', $this->type_document->code)
                    -> where('name', $this->type_document->name)
                    -> etc()
                )
        );
    }

    public function test_can_update_type_document(): void
    {
        $name = fake()->words(2, true);

        $response = $this->actingAs($this->user)
        ->patch(route('type_document.update', $this->type_document->id), [
            'name' => $name,
        ]);

        $response->assertFound();

        $this->type_document->refresh();
        $this->assertSame($name, $this->type_document->name);
        $this->assertDatabaseHas('type_documents', [
            'name' => $name,
        ]);

        $response->assertRedirect(route('type_document.index'))
        ->assertSessionHasAll(['success' => 'Type document updated.']);
    }
}
