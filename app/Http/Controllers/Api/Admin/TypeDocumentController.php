<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\User\Models\TypeDocument;
use App\Http\Controllers\Controller;
use App\Http\Resources\TypeDocumentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TypeDocumentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $type_documents = TypeDocument::query()->queryBuilderIndex()
            ->get();

        return TypeDocumentResource::collection($type_documents);
    }
}
