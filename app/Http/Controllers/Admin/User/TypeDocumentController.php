<?php

namespace App\Http\Controllers\Admin\User;

use App\Classes\User\TypesDocuments;
use App\Dtos\User\TypeDocument\StoreTypeDocumentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\TypeDocument\StoreRequest;
use App\Http\Requests\Admin\User\TypeDocument\UpdateRequest;
use App\Services\User\TypesDocumentsServices;
use App\Traits\useCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TypeDocumentController extends Controller
{
    use useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('User/TypeDocument/Index', [
            'typeDocuments' => $this->getTypeDocument(),
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('User/TypeDocument/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, TypesDocumentsServices $service): RedirectResponse
    {
        $data = StoreTypeDocumentData::fromRequest($request);
        $service->store($data);

        return Redirect::route(('type_document.index'))->with('success', 'Type document created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypesDocumentsServices $service, string $id): Response
    {
        return Inertia::render('User/TypeDocument/Edit', [
            'typeDocument' => $service->edit($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, TypesDocumentsServices $service, string $id): RedirectResponse
    {
        $service->update($id, $request->validated());
        return Redirect::route('type_document.index')->with('success', 'Type document updated.');
    }

}
