<?php

namespace App\Http\Controllers;

use App\Services\StartFactory;
use App\Traits\AuthHasRole;
use App\Traits\useCache;
use Illuminate\Http\RedirectResponse;

class StartController extends Controller
{
    use AuthHasRole, useCache;

    public function index(): RedirectResponse
    {
        $user_role = $this->authHasRole($this->getRoles());
        $start_factory = (new StartFactory())->initialize($user_role);
        return $start_factory->redirecting($user_role);
    }
}
