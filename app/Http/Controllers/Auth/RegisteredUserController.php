<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use App\Models\TypeDocument;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $cities = City::select('id', 'name', 'state_id')->get();
        $states = State::select('id', 'name')->get();
        $type_documents = TypeDocument::select('id', 'code', 'name')->get();

        return Inertia::render('Auth/Register', [
            'cities' => $cities,
            'states' => $states,
            'typeDocuments' => $type_documents,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'typeDocument' => 'required|string|max:3',
            'numberDocument' => 'required|regex:/^[0-9A-Z]+$/i|max:100|unique:users,number_document',
            'firstName' => 'required|string|max:100',
            'secondName' => 'nullable|string|max:100',
            'surname' => 'required|string|max:100',
            'seconSurname' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'birthdate' => 'required|date|before:18 years',
            'gender' => 'required|regex:/^[fmo]+$/i|max:1',
            'phone' => 'required|regex:/^[+\\-\\(\\)\\0-9x ]+$/i|max:100|unique:'.User::class,
            'address' => 'required|string|max:1000',
            'state' => 'required|integer',
            'city' => 'required|integer'
        ]);

        $user = User::create([
            'type_document' => $request->typeDocument,
            'number_document' => $request->numberDocument,
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'surname' => $request->surname,
            'second_surname' => $request->secondSurname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
            'state_id' => $request->state,
            'city_id' => $request->city
        ])->assignRole('client');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
