<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\User\Models\City;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $cities = City::query()->queryBuilderIndex()
            ->get();

        return CityResource::collection($cities);
    }
}
