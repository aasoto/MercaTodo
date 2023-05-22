<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\User\Actions\City\StoreCityAction;
use App\Domain\User\Actions\City\UpdateCityAction;
use App\Domain\User\Dtos\City\StoreCityData;
use App\Domain\User\Dtos\City\UpdateCityData;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\City\StoreRequest;
use App\Http\Requests\Web\Admin\City\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CityController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('User/City/Index', [
            'cities' => City::getFromCache(),
            'states' => State::getFromCache(),
            'success' => session('success'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('User/City/Create', [
            'states' => State::getFromCache(),
        ]);
    }

    public function store(StoreRequest $request, StoreCityAction $store_city_action): RedirectResponse
    {
        $data = StoreCityData::fromRequest($request);
        $store_city_action->handle($data);
        return Redirect::route('city.index')->with('success', 'City created.');
    }

    public function edit(string $id): Response
    {
        return Inertia::render('User/City/Edit', [
            'city' => City::where('id', $id)->first(),
            'states' => State::getFromCache(),
        ]);
    }

    public function update(UpdateRequest $request, UpdateCityAction $update_city_action, string $id): RedirectResponse
    {
        $data = UpdateCityData::fromRequest($request);
        $update_city_action->handle($id, $data);
        return Redirect::route('city.index')->with('success', 'City updated.');
    }
}
