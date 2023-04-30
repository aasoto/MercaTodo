<?php

namespace App\Http\Controllers\Admin\User;

use App\Classes\User\Action;
use App\Classes\User\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\Spatie\ModelHasRole;
use App\Models\User;
use App\Traits\useCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Action $user, string $role = "admin"): Response
    {
        return Inertia::render('User/Index', [
            'filters' => $request->only(['search', 'enabled']),
            'roleSearch' => $role,
            'roles' => $this->getRoles(),
            'success' => session('success'),
            'users' => $user->index($request, $role),
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
    public function store(StoreRequest $request, Action $user, Roles $roles): RedirectResponse
    {
        $data = $request->validated();
        $role = $roles->get($data["role_id"]);
        $user->create($data, $role ? $role['name'] : '');

        return Redirect::route('user.index', $role ? $role["name"] : '')
            -> with('success', 'User created.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Action $user, string $id): Response
    {
        return Inertia::render('User/Edit', [
            'cities' => $this->getCities(),
            'roles' => $this->getRoles(),
            'states' => $this->getStates(),
            'success' => session('success'),
            'typeDocuments' => $this->getTypeDocument(),
            'user' => $user->edit($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Action $user, Roles $roles, string $id): RedirectResponse
    {
        $data = $request->validated();
        $roles->update($id, $data["role_id"]);
        unset($data['role_id']);
        $user->update($id, $data);

        return Redirect::route('user.edit', $id)
            -> with('success', 'User updated.');

    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     //
    // }
}
