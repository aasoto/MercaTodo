<?php

namespace App\Http\Controllers\Admin\Product;

use App\Classes\Product\Units;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\Unit\StoreRequest;
use App\Http\Requests\Admin\Product\Unit\UpdateRequest;
use App\Traits\useCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UnitController extends Controller
{
    use useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Product/Unit/Index', [
            'units' => $this->getUnits(),
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Product/Unit/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Units $unit): RedirectResponse
    {
        $unit->create($request->validated());

        return Redirect::route(('unit.index'))->with('success', 'Unit created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Units $unit, string $id): Response
    {
        return Inertia::render('Product/Unit/Edit', [
            'unit' => $unit->edit($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Units $unit, string $id): RedirectResponse
    {
        $unit->update($id, $request->validated());
        return Redirect::route('unit.index')->with('success', 'Unit updated.');
    }
}
