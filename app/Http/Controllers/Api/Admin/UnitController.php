<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Product\Actions\Unit\StoreUnitAction;
use App\Domain\Product\Actions\Unit\UpdateUnitAction;
use App\Domain\Product\Dtos\Unit\StoreUnitData;
use App\Domain\Product\Dtos\Unit\UpdateUnitData;
use App\Domain\Product\Models\Unit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Unit\StoreRequest;
use App\Http\Requests\Api\Admin\Unit\UpdateRequest;
use App\Http\Resources\UnitResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UnitController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $units = Unit::query()->queryBuilderIndex()
            ->paginate(10);

        return UnitResource::collection($units);
    }

    public function store(
        StoreRequest $request,
        StoreUnitAction $store_unit_action): JsonResponse
    {
        $data = StoreUnitData::fromRequest($request);

        $unit = $store_unit_action->handle($data);

        return response()->json([
            'message' => trans('message.created', ['attribute' => 'data']),
            'data' => new UnitResource($unit),
        ], 201);
    }

    public function show(string $code): UnitResource | JsonResponse
    {
        $unit = Unit::query()->queryBuilderShow()
            ->whereCode($code)
            ->first();

        if ($unit) {
            return UnitResource::make($unit);
        } else {
            return response()->json([
                'message' => trans('not found'),
            ], 404);
        }
    }

    public function update(
        UpdateRequest $request,
        UpdateUnitAction $update_unit_action,
        string $id): JsonResponse
    {
        $data = UpdateUnitData::fromRequest($request);

        $response = $update_unit_action->handle($id, $data);

        if ($response) {
            return response()->json([
                'message' => trans('message.updated', ['attribute' => 'product']),
                'data' => new UnitResource(Unit::query()->findOrFail($id)),
            ], 200);
        } else {
            return response()->json([
                'message' => trans('not found'),
            ], 404);
        }
    }
}
