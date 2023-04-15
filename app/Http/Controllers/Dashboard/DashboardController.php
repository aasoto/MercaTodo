<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use App\Traits\AuthHasRole;
use App\Traits\useCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    use AuthHasRole, useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $roles = $this->getRoles();

        $user_role = $this->authHasRole($roles);

        return Inertia::render('Dashboard', [
            'userRole' => $user_role,
        ]);
    }

    /*public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(string $id)
    {

    }

    public function edit(string $id)
    {

    }

    public function update(Request $request, string $id)
    {

    }

    public function destroy(string $id)
    {

    }*/
}
