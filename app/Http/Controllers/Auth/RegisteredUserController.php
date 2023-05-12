<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Register\StoreRegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUser\StoreRequest;
use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'cities' => City::getFromCache(),
            'states' => State::getFromCache(),
            'typeDocuments' => TypeDocument::getFromCache(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(
        StoreRequest $request,
        StoreRegisterAction $store_register_action
    ): RedirectResponse
    {
        $user = $store_register_action->handle($request);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
