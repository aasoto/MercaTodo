<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\User\Models\TypeDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TypeDocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_list_of_types_document(): void
    {
        /**
         * @var TypeDocument $type_document
         */
        $type_document = TypeDocument::factory()->create();

        $response = $this->getJson(route('api.type_document.index'));

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', $type_document->id)
                    ->where('code', $type_document->code)
                    ->where('name', $type_document->name)
                    ->etc()
                )
        );
    }
}
