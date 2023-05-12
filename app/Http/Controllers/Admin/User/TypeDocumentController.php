<?php

namespace App\Http\Controllers\Admin\User;

use App\Classes\User\TypesDocuments;
use App\Dtos\User\TypeDocument\StoreTypeDocumentData;
use App\Dtos\User\TypeDocument\UpdateTypeDocumentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\TypeDocument\StoreRequest;
use App\Http\Requests\Admin\User\TypeDocument\UpdateRequest;
use App\Models\TypeDocument;
use App\Services\User\TypesDocumentsServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TypeDocumentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('User/TypeDocument/Index', [
            'typeDocuments' => TypeDocument::getFromCache(),
            'success' => session('success'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('User/TypeDocument/Create');
    }

    public function store(StoreRequest $request, TypesDocumentsServices $types_documents_services): RedirectResponse
    {
        $data = StoreTypeDocumentData::fromRequest($request);
        $types_documents_services->store($data);

        return Redirect::route(('type_document.index'))->with('success', 'Type document created.');
    }

    public function edit(TypesDocumentsServices $types_documents_services, string $id): Response
    {
        return Inertia::render('User/TypeDocument/Edit', [
            'typeDocument' => $types_documents_services->edit($id),
        ]);
    }

    public function update(UpdateRequest $request, TypesDocumentsServices $types_documents_services, string $id): RedirectResponse
    {
        $data = UpdateTypeDocumentData::fromRequest($request);

        $types_documents_services->update($id, $data);

        return Redirect::route('type_document.index')->with('success', 'Type document updated.');
    }

}
