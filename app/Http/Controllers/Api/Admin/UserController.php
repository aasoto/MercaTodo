<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $users = User::query()->queryBuilderIndex()
            ->paginate(10);

        return UserResource::collection($users);
    }
}
