<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LogoutRequest;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LogoutRequest $request): void
    {
        /**
         * @var User $user
         */
        $user = User::query()->where('id', $request->validated()['user_id'])->first();
        $user->tokens()->delete();
    }
}
