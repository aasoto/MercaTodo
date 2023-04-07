<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\StoreRequest;
use App\Http\Requests\Dashboard\User\UpdateRequest;
use App\Models\City;
use App\Models\Spatie\ModelHasRole;
use App\Models\State;
use App\Models\TypeDocument;
use App\Models\User;
use App\Traits\AuthHasRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthHasRole;
    /**
     * Display a listing of the resource.
     */
    public function index(string $role = "admin"): Response
    {
        $roles = Cache::get('roles');
        $role_id = 0;

        foreach ($roles as $key => $value) {
            if ($value['name'] === $role) {
                $role_id = $value['id'];
            }
        }

        $users = User::query()
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
            -> where('model_has_roles.role_id', $role_id)
            -> paginate(10);

        $user_role = $this->authHasRole($roles);

        return Inertia::render('User/Index', [
            'roleSearch' => $role,
            'roles' => $roles,
            'users' => $users,
            'userRole' => $user_role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $cities = Cache::get('cities');
        $roles = Cache::get('roles');
        $states = Cache::get('states');
        $type_documents = Cache::get('type_documents');

        $user_role = $this->authHasRole($roles);

        return Inertia::render('User/Create', [
            'cities' => $cities,
            'roles' => $roles,
            'states' => $states,
            'typeDocuments' => $type_documents,
            'userRole' => $user_role,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $role = Role::select('name')->where('id', $data["role_id"])->first();

        User::create([
            "type_document" => $data["type_document"],
            "number_document" => $data["number_document"],
            "first_name" => $data["first_name"],
            "second_name" => $data["second_name"],
            "surname" => $data["surname"],
            "email" => $data["email"],
            "password" => Hash::make($data["number_document"]),
            "birthdate" => $data["birthdate"],
            "gender" => $data["gender"],
            "phone" => $data["phone"],
            "address" => $data["address"],
            "state_id" => $data["state_id"],
            "city_id" => $data["city_id"]
        ])
            -> assignRole($role["name"])
            -> sendEmailVerificationNotification();

        return Redirect::route('user.index', $data['role_id']);
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
        -> first();

        $cities = Cache::get('cities');
        $roles = Cache::get('roles');
        $states = Cache::get('states');
        $type_documents = Cache::get('type_documents');

        $user_role = $this->authHasRole($roles);

        return Inertia::render('User/Edit', [
            'cities' => $cities,
            'roles' => $roles,
            'states' => $states,
            'user' => $user,
            'userRole' => $user_role,
            'typeDocuments' => $type_documents,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        ModelHasRole::where('model_id', $id)
            ->update(["role_id" => $data["role_id"]]);
        unset($data['role_id']);
        User::where('id', $id)->update($data);

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
