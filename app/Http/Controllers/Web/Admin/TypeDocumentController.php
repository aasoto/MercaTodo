<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\User\Actions\TypeDocument\StoreTypeDocumentAction;
use App\Domain\User\Actions\TypeDocument\UpdateTypeDocumentAction;
use App\Domain\User\Dtos\TypeDocument\StoreTypeDocumentData;
use App\Domain\User\Dtos\TypeDocument\UpdateTypeDocumentData;
use App\Domain\User\Models\TypeDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\TypeDocument\StoreRequest;
use App\Http\Requests\Web\Admin\TypeDocument\UpdateRequest;
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

    public function store(StoreRequest $request, StoreTypeDocumentAction $store_type_document_action): RedirectResponse
    {
        $data = StoreTypeDocumentData::fromRequest($request);
        $store_type_document_action->handle($data);

        return Redirect::route(('type_document.index'))->with('success', 'Type document created.');
    }

    public function edit(string $id): Response
    {
        return Inertia::render('User/TypeDocument/Edit', [
            'typeDocument' => TypeDocument::where('id', $id)->first(),
        ]);
    }

    public function update(UpdateRequest $request, UpdateTypeDocumentAction $update_type_document_action, string $id): RedirectResponse
    {
        $data = UpdateTypeDocumentData::fromRequest($request);

        $update_type_document_action->handle($id, $data);

        return Redirect::route('type_document.index')->with('success', 'Type document updated.');
    }

}
