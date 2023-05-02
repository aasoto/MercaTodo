<?php

namespace App\Http\Controllers\Admin\User;

use App\Classes\User\TypesDocuments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\TypeDocument\StoreRequest;
use App\Http\Requests\Admin\User\TypeDocument\UpdateRequest;
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
    public function store(StoreRequest $request, TypesDocuments $type_document): RedirectResponse
    {
        // dd($request->validated());
        $type_document->create($request->validated());

        return Redirect::route(('type_document.index'))->with('success', 'Type document created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypesDocuments $type_document, string $id): Response
    {
        return Inertia::render('User/TypeDocument/Edit', [
            'typeDocument' => $type_document->edit($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, TypesDocuments $type_document, string $id): RedirectResponse
    {
        $type_document->update($id, $request->validated());
        return Redirect::route('type_document.index')->with('success', 'Type document updated.');
    }

}
