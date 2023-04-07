<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Traits\AuthHasRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    use AuthHasRole;
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $cities = Cache::get('cities');
        $states = Cache::get('states');
        $type_documents = Cache::get('type_documents');

        $role = $this->authHasRole(Role::select('id', 'name')->get());

        return Inertia::render('Profile/Edit', [
            'cities' => $cities,
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'role' => $role,
            'states' => $states,
            'status' => session('status'),
            'typeDocuments' => $type_documents,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
