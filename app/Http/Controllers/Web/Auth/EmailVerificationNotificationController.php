<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Jobs\SendEmailVerificationJob;
use App\Support\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()?->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        SendEmailVerificationJob::dispatch($request->user())->onQueue('email_verification');

        return back()->with('status', 'verification-link-sent');
    }
}
