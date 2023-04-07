<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use App\Traits\AuthHasRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    use AuthHasRole;
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        if (Cache::has('cities')) {
            $cities = Cache::get('cities');
        } else {
            $cities = City::select('id', 'name', 'state_id')->get();
            Cache::put('cities', $cities);
        }

        if (Cache::has('roles')) {
            $roles = Cache::get('roles');
        } else {
            $roles = Role::select('id', 'name')->get();
            Cache::put('roles', $roles);
        }

        if (Cache::has('states')) {
            $states = Cache::get('states');
        } else {
            $states = State::select('id', 'name')->get();
            Cache::put('states', $states);
        }

        if (Cache::has('type_documents')) {
            $type_documents = Cache::get('type_documents');
        } else {
            $type_documents = TypeDocument::select('id', 'code', 'name')->get();
            Cache::put('type_documents', $type_documents);
        }

        $user_role = $this->authHasRole($roles);

        return Inertia::render('Dashboard', [
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
