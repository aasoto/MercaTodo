<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\User\EditUserAction;
use App\Actions\User\IndexUserAction;
use App\Actions\User\StoreUserAction;
use App\Actions\User\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Services\User\RolesServices;
use App\Traits\useCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    use useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, IndexUserAction $action, string $role = "admin"): Response
    {
        return Inertia::render('User/Index', [
            'filters' => $request->only(['search', 'enabled']),
            'roleSearch' => $role,
            'roles' => $this->getRoles(),
            'success' => session('success'),
            'users' => $action->handle($request, $role),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('User/Create', [
            'cities' => $this->getCities(),
            'roles' => $this->getRoles(),
            'states' => $this->getStates(),
            'typeDocuments' => $this->getTypeDocument(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, RolesServices $service, StoreUserAction $action): RedirectResponse
    {
        $role = $action->handle($request, $service);

        return Redirect::route('user.index', $role)
            -> with('success', 'User created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditUserAction $action, string $id): Response
    {
        return Inertia::render('User/Edit', [
            'cities' => $this->getCities(),
            'roles' => $this->getRoles(),
            'states' => $this->getStates(),
            'success' => session('success'),
            'typeDocuments' => $this->getTypeDocument(),
            'user' => $action->handle($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, RolesServices $service, UpdateUserAction $action, string $id): RedirectResponse
    {

        $action->handle($request, $service, $id);

        return Redirect::route('user.edit', $id)
            -> with('success', 'User updated.');

    }

}
