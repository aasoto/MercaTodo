<?php

namespace App\Http\Controllers\Admin\Product;

use App\Actions\Product\DestroyProductAction;
use App\Actions\Product\EditProductAction;
use App\Actions\Product\IndexProductAction;
use App\Actions\Product\ShowProductAction;
use App\Actions\Product\StoreProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Classes\Product\Action;
use App\Classes\Product\Images;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Traits\useCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    use useCache;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, IndexProductAction $action): Response
    {

        return Inertia::render('Product/Index', [
            'filters' => $request->only(['search', 'category', 'availability']),
            'products_categories' => $this->getProductsCategories(),
            'products' => $action->handle($request),
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Product/Create', [
            'products_categories' => $this->getProductsCategories(),
            'units' => $this->getUnits(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreRequest $request,
        Images $images,
        StoreProductAction $action): RedirectResponse
    {
        $action->handle($request, $images);

        return Redirect::route('products.index')->with('success', 'Product created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug, ShowProductAction $action): Response
    {
        return Inertia::render('Product/Show', [
            'product' => $action->handle($slug),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug, EditProductAction $action): Response
    {
        return Inertia::render('Product/Edit', [
            'product' => $action->handle($slug),
            'products_categories' => $this->getProductsCategories(),
            'units' => $this->getUnits(),
            'success' => session('success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateRequest $request,
        Images $images,
        UpdateProductAction $action,
        string $id,
        string $files): RedirectResponse
    {

        $slug = $action->handle($request, $images, $id, $files);

        return Redirect::route('product.edit', $slug)->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Images $images,
        DestroyProductAction $action,
        ShowProductAction $showAction,
        string $slug): RedirectResponse
    {

        $action->handle($images, $showAction, $slug);

        return Redirect::route('products.index')->with('success', 'Product deleted.');
    }
}
