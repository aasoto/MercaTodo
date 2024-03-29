<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Product\Actions\DestroyProductAction;
use App\Domain\Product\Actions\StoreProductAction;
use App\Domain\Product\Actions\UpdateProductAction;
use App\Domain\Product\Dtos\StoreProductData;
use App\Domain\Product\Dtos\UpdateProductData;
use App\Domain\Product\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Product\StoreRequest;
use App\Http\Requests\Api\Admin\Product\UpdateRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::query()->queryBuilderIndex()
            ->paginate(10);

        return ProductResource::collection($products);
    }

    public function store(
        StoreRequest $request,
        StoreProductAction $store_product_action): JsonResponse
    {
        $data = StoreProductData::fromRequest($request);

        $product = $store_product_action->handle($data);

        return response()->json([
            'message' => trans('message.created', ['attribute' => 'product']),
            'data' => new ProductResource($product),
        ], 201);
    }

    public function show(string $slug): ProductResource | JsonResponse
    {
        $product = Product::query()->queryBuilderShow()
            ->whereSlug($slug)
            ->first();

        if ($product) {
            return ProductResource::make($product);
        } else {
            return response()->json([
                'message' => trans('not found'),
            ], 404);
        }
    }

    public function update(
        UpdateRequest $request,
        UpdateProductAction $update_product_action,
        string $id): JsonResponse
    {
        /**
         * @var Product $product
         */
        $product = Product::query()->where('id', $id)->first();

        if (!isset($product->name)) {
            return response()->json([
                'message' => trans('not found'),
            ], 404);
        }

        $files = array(
            'picture_1' => $product->picture_1,
            'picture_2' => $product->picture_2,
            'picture_3' => $product->picture_3,
        );

        $data = UpdateProductData::fromRequest($request);

        /**
         * @var string $encode_files
         */
        $encode_files = json_encode($files);
        $update_product_action->handle($data, $id, $encode_files);

        return response()->json([
            'message' => trans('message.updated', ['attribute' => 'product']),
            'data' => new ProductResource($product->fresh()),
        ], 200);
    }

    public function destroy(
        DestroyProductAction $destroy_product_action,
        string $slug): JsonResponse
    {
        $result = $destroy_product_action->handle($slug);

        if ($result) {
            return response()->json([
                'message' => trans('message.deleted'),
            ], 200);
        } else {
            return response()->json([
                'message' => trans('not found'),
            ], 404);
        }
    }
}
