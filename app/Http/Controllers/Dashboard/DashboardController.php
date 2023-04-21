<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\AuthHasRole;
use App\Traits\useCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use AuthHasRole, useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(): Response | RedirectResponse
    {
        $user_role = $this->authHasRole($this->getRoles());

        if ($user_role == 'admin') {
            return Inertia::render('Dashboard', [
                'userRole' => $user_role,
            ]);
        }

        if ($user_role == 'client') {
            return Redirect::route('showcase.index');
        }
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
