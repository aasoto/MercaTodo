<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\User\EditUserAction;
use App\Actions\User\IndexUserAction;
use App\Actions\User\StoreUserAction;
use App\Actions\User\UpdateUserAction;
use App\Dtos\User\StoreUserData;
use App\Dtos\User\UpdateUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\City;
use App\Models\Spatie\ModelHasRole as Role;
use App\Models\State;
use App\Models\TypeDocument;
use App\Services\User\RolesServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, IndexUserAction $index_user_action, string $role = "admin"): Response
    {
        return Inertia::render('User/Index', [
            'filters' => $request->only(['search', 'enabled']),
            'roleSearch' => $role,
            'roles' => Role::getFromCache(),
            'success' => session('success'),
            'users' => $index_user_action->handle($request, $role),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('User/Create', [
            'cities' => City::getFromCache(),
            'roles' => Role::getFromCache(),
            'states' => State::getFromCache(),
            'typeDocuments' => TypeDocument::getFromCache(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, StoreUserAction $store_user_action): RedirectResponse
    {
        $data = StoreUserData::fromRequest($request);

        $role = $store_user_action->handle($data);

        return Redirect::route('user.index', $role)
            -> with('success', 'User created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditUserAction $edit_user_action, string $id): Response
    {
        return Inertia::render('User/Edit', [
            'cities' => City::getFromCache(),
            'roles' => Role::getFromCache(),
            'states' => State::getFromCache(),
            'success' => session('success'),
            'typeDocuments' => TypeDocument::getFromCache(),
            'user' => $edit_user_action->handle($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, UpdateUserAction $update_user_action, string $id): RedirectResponse
    {
        $data = UpdateUserData::fromRequest($request);

        $update_user_action->handle($data, $id);

        return Redirect::route('user.edit', $id)
            -> with('success', 'User updated.');

    }

}
