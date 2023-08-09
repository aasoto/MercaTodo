<?php

namespace App\Http\Controllers\Api\Client;

use App\Domain\Product\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShowcaseController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::query()->queryBuilderIndex()
            ->paginate(10);

        return ProductResource::collection($products);
    }
}
