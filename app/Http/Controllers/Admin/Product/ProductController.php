<?php

namespace App\Http\Controllers\Admin\Product;

use App\Actions\Product\DestroyProductAction;
use App\Actions\Product\EditProductAction;
use App\Actions\Product\IndexProductAction;
use App\Actions\Product\ShowProductAction;
use App\Actions\Product\StoreProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Dtos\Product\StoreProductData;
use App\Dtos\Product\UpdateProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, IndexProductAction $index_product_action): Response
    {

        return Inertia::render('Product/Index', [
            'filters' => $request->only(['search', 'category', 'availability']),
            'products_categories' => ProductCategory::getFromCache(),
            'products' => $index_product_action->handle($request),
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Product/Create', [
            'products_categories' => ProductCategory::getFromCache(),
            'units' => Unit::getFromCache(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreRequest $request,
        StoreProductAction $store_product_action): RedirectResponse
    {
        $data = StoreProductData::fromRequest($request);

        $store_product_action->handle($data);

        return Redirect::route('products.index')->with('success', 'Product created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug, ShowProductAction $show_product_action): Response
    {
        return Inertia::render('Product/Show', [
            'product' => $show_product_action->handle($slug),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug, EditProductAction $edit_product_action): Response
    {
        return Inertia::render('Product/Edit', [
            'product' => $edit_product_action->handle($slug),
            'products_categories' => ProductCategory::getFromCache(),
            'units' => Unit::getFromCache(),
            'success' => session('success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateRequest $request,
        UpdateProductAction $update_product_action,
        string $id,
        string $files): RedirectResponse
    {

        $data = UpdateProductData::fromRequest($request);

        $slug = $update_product_action->handle($data, $id, $files);

        return Redirect::route('product.edit', $slug)->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DestroyProductAction $destroy_product_action,
        string $slug): RedirectResponse
    {

        $destroy_product_action->handle($slug);

        return Redirect::route('products.index')->with('success', 'Product deleted.');
    }
}
