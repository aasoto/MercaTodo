<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => trans('auth.failed'),
            ], 422);
        }

        /**
         * @var User $user
         */
        $user = User::query()->where('email', $request->get('email'))->first();
        $token = $user->createToken(Str::random())->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }
}
