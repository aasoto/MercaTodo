<?php

namespace App\Http\Controllers\Admin\User;

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
    public function index(Request $request, string $role = "admin"): Response
    {
        return Inertia::render('User/Index', [
            'filters' => $request->only(['search', 'enabled']),
            'roleSearch' => $role,
            'roles' => $this->getRoles(),
            'success' => session('success'),
            'users' => User::query()
                -> when($request->input('search'), function ($query, $search) {
                    $query->where('users.email', 'like', '%'.$search.'%')
                    ->orWhere('users.number_document', 'like', '%'.$search.'%')
                    ->orWhere('users.first_name', 'like', '%'.$search.'%')
                    ->orWhere('users.second_name', 'like', '%'.$search.'%')
                    ->orWhere('users.surname', 'like', '%'.$search.'%')
                    ->orWhere('users.second_surname', 'like', '%'.$search.'%')
                    ->orWhere('cities.name', 'like', '%'.$search.'%')
                    ->orWhere('states.name', 'like', '%'.$search.'%');
                })
                -> when($request->input('enabled'), function ($query, $search) {
                    if ($search == 'true') {
                        $query->where('users.enabled', '1');
                    }
                    if ($search == 'false') {
                        $query->where('users.enabled', '0');
                    }
                })
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
                -> orderBy('users.id')
                -> role($role)
                -> paginate(10)
                -> withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $cities = $this->getCities();
        $roles = $this->getRoles();
        $states = $this->getStates();
        $type_documents = $this->getTypeDocument();

        return Inertia::render('User/Create', [
            'cities' => $cities,
            'roles' => $roles,
            'states' => $states,
            'typeDocuments' => $type_documents,
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

        return Redirect::route('user.index', $role["name"])
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

        $cities = $this->getCities();
        $roles = $this->getRoles();
        $states = $this->getStates();
        $type_documents = $this->getTypeDocument();

        return Inertia::render('User/Edit', [
            'cities' => $cities,
            'roles' => $roles,
            'states' => $states,
            'success' => session('success'),
            'typeDocuments' => $type_documents,
            'user' => $user,
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
