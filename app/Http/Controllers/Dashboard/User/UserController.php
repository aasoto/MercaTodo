<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\UpdateRequest;
use App\Models\City;
use App\Models\Spatie\ModelHasRole;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = User::query()
            -> select(
                    'users.id',
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
            -> paginate(10);

        $roles = Role::select('id', 'name')->get();

        $user_role = '';
        foreach (Role::all() as $key => $value) {
            if (Auth::user()->hasRole($value['name'])) {
                $user_role = $value['name'];
            }
        }

        return Inertia::render('User/Index', [
            'roles' => $roles,
            'users' => $users,
            'userRole' => $user_role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $user = User::select(
                'users.id',
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
        -> first();

        $cities = City::select('id', 'name', 'state_id')->get();
        $roles = Role::select('id', 'name')->get();
        $states = State::select('id', 'name')->get();

        $user_role = '';
        foreach (Role::all() as $key => $value) {
            if (Auth::user()->hasRole($value['name'])) {
                $user_role = $value['name'];
            }
        }

        return Inertia::render('User/Edit', [
            'cities' => $cities,
            'roles' => $roles,
            'states' => $states,
            'user' => $user,
            'userRole' => $user_role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $user_updated_rol = ModelHasRole::where('model_id', $id)
            ->update(["role_id" => $data["role_id"]]);
        unset($data['role_id']);
        $user_updated = User::where('id', $id)->update($data);

        return Redirect::route('user.edit', $id);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
