<?php

namespace App\Http\Controllers\Admin;

use App\Traits\AuthHasRole;
use App\Traits\useCache;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use AuthHasRole, useCache;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'userRole' => session('user_role') ? session('user_role') : $this->authHasRole($this->getRoles()),
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