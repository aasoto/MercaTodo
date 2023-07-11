<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domain\User\Actions\StoreRegisterAction;
use App\Domain\User\Dtos\StoreRegisterData;
use App\Http\Controllers\Controller;
use App\Http\Jobs\SendEmailVerificationJob;
use App\Http\Requests\Api\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __invoke(
        RegisterRequest $request,
        StoreRegisterAction $store_register_action,
        string $role): JsonResponse
    {
        $data = StoreRegisterData::fromRequest($request);

        if ($role == 'admin') {
            $user = $store_register_action->handle($data, $role);
        } else {
            $user = $store_register_action->handle($data);
        }

        SendEmailVerificationJob::dispatch($user);

        return response()->json([
            'message' => trans('message.created', ['attribute' => 'user']),
            'user' => $user->only([
                'first_name',
                'second_name',
                'surname',
                'second_surname',
                'email',
            ]),
        ], 201);
    }
}
