<?php

namespace App\Classes\Start;

use App\Contracts\StartInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ClientMode implements StartInterface
{
    public function redirecting(string $user_role): RedirectResponse
    {
        return Redirect::route('showcase.index')->with('user_role', $user_role);
    }
}
