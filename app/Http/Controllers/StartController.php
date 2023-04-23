<?php

namespace App\Http\Controllers;

use App\Traits\AuthHasRole;
use App\Traits\useCache;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class StartController extends Controller
{
    use AuthHasRole, useCache;

    public function index(): RedirectResponse
    {
        $user_role = $this->authHasRole($this->getRoles());

        if ($user_role == 'admin') {
            return Redirect::route('dashboard.index')->with('user_role', $user_role);
        }

        if ($user_role == 'client') {
            return Redirect::route('showcase.index')->with('user_role', $user_role);
        }
    }
}
