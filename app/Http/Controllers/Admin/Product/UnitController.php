<?php

namespace App\Http\Controllers\Admin\Product;

use App\Dtos\Product\Unit\StoreUnitData;
use App\Dtos\Product\Unit\UpdateUnitData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\Unit\StoreRequest;
use App\Http\Requests\Admin\Product\Unit\UpdateRequest;
use App\Models\Unit;
use App\Services\Product\UnitsServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UnitController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Product/Unit/Index', [
            'units' => Unit::getFromCache(),
            'success' => session('success'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Product/Unit/Create');
    }

    public function store(StoreRequest $request, UnitsServices $units_services): RedirectResponse
    {
        $data = StoreUnitData::fromRequest($request);

        $units_services->store($data);

        return Redirect::route(('unit.index'))->with('success', 'Unit created.');
    }

    public function edit(UnitsServices $units_services, string $id): Response
    {
        return Inertia::render('Product/Unit/Edit', [
            'unit' => $units_services->edit($id),
        ]);
    }

    public function update(UpdateRequest $request, UnitsServices $units_services, string $id): RedirectResponse
    {
        $data = UpdateUnitData::fromRequest($request);
        $units_services->update($id, $data);
        return Redirect::route('unit.index')->with('success', 'Unit updated.');
    }
}
