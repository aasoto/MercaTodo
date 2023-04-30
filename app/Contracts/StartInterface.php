<?php

namespace App\Contracts;

use Illuminate\Http\RedirectResponse;

interface StartInterface {

    public function redirecting(string $user_role): RedirectResponse;

}
