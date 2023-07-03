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
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $posts = QueryBuilder::for(Product::class)
            ->allowedFilters(['name', 'price', 'availability', 'products_category_id', 'unit'])
            ->allowedIncludes('products_category')
            ->paginate(10);

        return ProductResource::collection($posts);
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

    public function show(string $slug): ProductResource
    {
        $product = QueryBuilder::for(Product::class)
            ->allowedIncludes('products_category')
            ->where('slug', $slug)
            ->first();

        return ProductResource::make($product);
    }

    public function update(
        UpdateRequest $request,
        UpdateProductAction $update_product_action,
        string $id): JsonResponse
    {
        /**
         * @var Product $product
         */
        $product = Product::query()->findOrFail($id);

        $files = array();
        array_push($files, [
            'picture_1' => $product->picture_1,
            'picture_2' => $product->picture_2,
            'picture_3' => $product->picture_3,
        ]);

        $data = UpdateProductData::fromRequest($request);

        $update_product_action->handle($data, $id, json_encode($files));

        return response()->json([
            'message' => trans('message.updated', ['attribute' => 'product']),
            'data' => new ProductResource($product),
        ], 200);
    }

    public function destroy(
        DestroyProductAction $destroy_product_action,
        string $slug)
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
