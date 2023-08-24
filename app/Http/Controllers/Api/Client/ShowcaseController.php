<?php

namespace App\Http\Controllers\Api\Client;

use App\Domain\Product\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShowcaseController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::query()->queryBuilderIndex()
            ->paginate(12);

        return ProductResource::collection($products);
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
}
