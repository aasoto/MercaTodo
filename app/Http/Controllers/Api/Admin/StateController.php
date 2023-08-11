<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\User\Models\State;
use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StateController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $states = State::query()->queryBuilderIndex()
            ->get();

        return StateResource::collection($states);
    }
}
