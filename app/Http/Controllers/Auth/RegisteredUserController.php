<?php

namespace App\Http\Controllers\Auth;

use App\Classes\User\Action;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUser\StoreRequest;
use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\useCache;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    use useCache;
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'cities' => $this->getCities(),
            'states' => $this->getStates(),
            'typeDocuments' => $this->getTypeDocument(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreRequest $request, Action $user): RedirectResponse
    {
        $user = $user->register($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
