<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\User\Actions\State\StoreStateAction;
use App\Domain\User\Actions\State\UpdateStateAction;
use App\Domain\User\Dtos\State\StoreStateData;
use App\Domain\User\Dtos\State\UpdateStateData;
use App\Domain\User\Models\State;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\State\StoreRequest;
use App\Http\Requests\Web\Admin\State\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class StateController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('User/State/Index', [
            'states' => State::getFromCache(),
            'success' => session('success'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('User/State/Create');
    }

    public function store(StoreRequest $request, StoreStateAction $store_state_action): RedirectResponse
    {
        $data = StoreStateData::fromRequest($request);
        $store_state_action->handle($data);
        return Redirect::route('state.index')->with('success', 'State created.');
    }

    public function edit(string $id): Response
    {
        return Inertia::render('User/State/Edit', [
            'state' => State::select('id', 'name')->where('id', $id)->first(),
        ]);
    }

    public function update(UpdateRequest $request, UpdateStateAction $update_state_action, string $id): RedirectResponse
    {
        $data = UpdateStateData::fromRequest($request);
        $update_state_action->handle($id, $data);
        return Redirect::route('state.index')->with('success', 'State updated.');
    }
}
