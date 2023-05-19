<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Actions\Unit\StoreUnitAction;
use App\Domain\Product\Actions\Unit\UpdateUnitAction;
use App\Domain\Product\Dtos\Unit\StoreUnitData;
use App\Domain\Product\Dtos\Unit\UpdateUnitData;
use App\Domain\Product\Models\Unit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Unit\StoreRequest;
use App\Http\Requests\Admin\Unit\UpdateRequest;
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

    public function store(StoreRequest $request, StoreUnitAction $store_unit_action): RedirectResponse
    {
        $data = StoreUnitData::fromRequest($request);

        $store_unit_action->handle($data);

        return Redirect::route(('unit.index'))->with('success', 'Unit created.');
    }

    public function edit(string $id): Response
    {
        return Inertia::render('Product/Unit/Edit', [
            'unit' => Unit::where('id', $id)->first(),
        ]);
    }

    public function update(UpdateRequest $request, UpdateUnitAction $update_unit_action, string $id): RedirectResponse
    {
        $data = UpdateUnitData::fromRequest($request);
        $update_unit_action->handle($id, $data);
        return Redirect::route('unit.index')->with('success', 'Unit updated.');
    }
}
