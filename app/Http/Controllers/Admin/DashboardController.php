<?php

namespace App\Http\Controllers\Admin;

use App\Traits\AuthHasRole;
use App\Http\Controllers\Controller;
use App\Models\Spatie\ModelHasRole as Role;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use AuthHasRole;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'userRole' =>
                session('user_role') ?
                session('user_role') :
                $this->authHasRole(Role::getFromCache()),
        ]);
    }

}
