<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\User\Models\ModelHasRole as Role;
use App\Domain\User\Traits\AuthHasRole;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use AuthHasRole;

    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'userRole' =>
                session('user_role') ? session('user_role') : $this->authHasRole(Role::getFromCache()),
        ]);
    }

}
