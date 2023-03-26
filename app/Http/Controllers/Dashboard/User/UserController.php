<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\Spatie\ModelHasRol;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()
            -> select(
                    'users.first_name',
                    'users.second_name',
                    'users.surname',
                    'users.second_surname',
                    'users.email',
                    'states.name as state_name',
                    'cities.name as city_name',
                    'model_has_roles.role_id'
                )
            -> join('states', 'users.state_id', 'states.id')
            -> join('cities', 'users.city_id', 'cities.id')
            -> join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            -> get();

        $roles = Role::select('id', 'name')->get();

        // dd($users, $roles);
        return Inertia::render('User/Index', [
            'users' => $users,
            'roles' => $roles
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
