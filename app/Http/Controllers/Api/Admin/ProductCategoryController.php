<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Product\Actions\Category\StoreProductCategoryAction;
use App\Domain\Product\Actions\Category\UpdateProductCategoryAction;
use App\Domain\Product\Dtos\Category\StoreProductCategoryData;
use App\Domain\Product\Dtos\Category\UpdateProductCategoryData;
use App\Domain\Product\Models\ProductCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\ProductCategory\StoreRequest;
use App\Http\Requests\Api\Admin\ProductCategory\UpdateRequest;
use App\Http\Resources\ProductsCategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products_categories = ProductCategory::query()->queryBuilderIndex()
            ->get();

        return ProductsCategoryResource::collection($products_categories);
    }

    public function store(
        StoreRequest $request,
        StoreProductCategoryAction $store_product_category_action): JsonResponse
    {
        $data = StoreProductCategoryData::fromRequest($request);

        $product_category = $store_product_category_action->handle($data);

        return response()->json([
            'message' => trans('message.created', ['attribute' => 'data']),
            'data' => new ProductsCategoryResource($product_category),
        ], 201);
    }

    public function show(string $id): ProductsCategoryResource | JsonResponse
    {
        $product_category = ProductCategory::query()->queryBuilderShow()
            ->whereId($id)
            ->first();

        if ($product_category) {
            return ProductsCategoryResource::make($product_category);
        } else {
            return response()->json([
                'message' => trans('not found'),
            ], 404);
        }
    }

    public function update(
        UpdateRequest $request,
        UpdateProductCategoryAction $update_product_category_action,
        string $id): JsonResponse
    {
        $data = UpdateProductCategoryData::fromRequest($request);

        $response = $update_product_category_action->handle($id, $data);

        if ($response) {
            return response()->json([
                'message' => trans('message.updated', ['attribute' => 'data']),
                'data' => new ProductsCategoryResource(ProductCategory::query()->findOrFail($id)),
            ], 200);
        } else {
            return response()->json([
                'message' => trans('not found'),
            ], 404);
        }
    }
}
