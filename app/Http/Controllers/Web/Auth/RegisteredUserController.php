<?php

namespace App\Http\Controllers\Web\Auth;

use App\Domain\User\Actions\StoreRegisterAction;
use App\Domain\User\Dtos\StoreRegisterData;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Http\Controllers\Controller;
use App\Http\Jobs\SendEmailVerificationJob;
use App\Http\Requests\Web\Auth\RegisteredUser\StoreRequest;
use App\Support\Providers\RouteServiceProvider;
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
        $data = StoreRegisterData::fromRequest($request);
        $user = $store_register_action->handle($data);

        // event(new Registered($user));

        SendEmailVerificationJob::dispatch($user);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
