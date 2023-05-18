<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\TypeDocument\Models\TypeDocument;
use App\Domain\User\Actions\StoreUserAction;
use App\Domain\User\Actions\UpdateUserAction;
use App\Domain\User\Dtos\StoreUserData;
use App\Domain\User\Dtos\UpdateUserData;
use App\Domain\User\Models\City;
use App\Domain\User\Models\ModelHasRole as Role;
use App\Domain\User\Models\State;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request, string $role = "admin"): Response
    {
        return Inertia::render('User/Index', [
            'filters' => $request->only(['search', 'enabled']),
            'roleSearch' => $role,
            'roles' => Role::getFromCache(),
            'success' => session('success'),
            'users' => User::query()
            -> whereSearch($request->input('search'))
            -> whenEnabled($request->input('enabled'))
            -> select(
                    'users.id',
                    'users.number_document',
                    'users.first_name',
                    'users.second_name',
                    'users.surname',
                    'users.second_surname',
                    'users.email',
                    'users.email_verified_at',
                    'users.enabled',
                    'states.name as state_name',
                    'cities.name as city_name',
                    'model_has_roles.role_id'
                )
            -> join('states', 'users.state_id', 'states.id')
            -> join('cities', 'users.city_id', 'cities.id')
            -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            -> orderByDesc('users.id')
            -> role($role)
            -> paginate(10)
            -> withQueryString(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('User/Create', [
            'cities' => City::getFromCache(),
            'roles' => Role::getFromCache(),
            'states' => State::getFromCache(),
            'typeDocuments' => TypeDocument::getFromCache(),
        ]);
    }

    public function store(StoreRequest $request, StoreUserAction $store_user_action): RedirectResponse
    {
        $data = StoreUserData::fromRequest($request);

        $role = $store_user_action->handle($data);

        return Redirect::route('user.index', $role)
            -> with('success', 'User created.');
    }

    public function edit(string $id): Response
    {
        return Inertia::render('User/Edit', [
            'cities' => City::getFromCache(),
            'roles' => Role::getFromCache(),
            'states' => State::getFromCache(),
            'success' => session('success'),
            'typeDocuments' => TypeDocument::getFromCache(),
            'user' => User::select(
                    'users.id',
                    'users.type_document',
                    'users.number_document',
                    'users.first_name',
                    'users.second_name',
                    'users.surname',
                    'users.second_surname',
                    'users.email',
                    'users.birthdate',
                    'users.gender',
                    'users.phone',
                    'users.address',
                    'users.enabled',
                    'users.state_id',
                    'users.city_id',
                    'model_has_roles.role_id'
                )
                -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
                -> where('users.id', $id)
                -> first(),
        ]);
    }

    public function update(UpdateRequest $request, UpdateUserAction $update_user_action, string $id): RedirectResponse
    {
        $data = UpdateUserData::fromRequest($request);

        $update_user_action->handle($data, $id);

        return Redirect::route('user.edit', $id)
            -> with('success', 'User updated.');

    }

}
