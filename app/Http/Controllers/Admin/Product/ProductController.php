<?php

namespace App\Http\Controllers\Admin\Product;

use App\Classes\Product\Action;
use App\Classes\Product\Images;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
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
    public function index(Request $request, Action $product): Response
    {

        return Inertia::render('Product/Index', [
            'filters' => $request->only(['search', 'category', 'availability']),
            'products_categories' => $this->getProductsCategories(),
            'products' => $product->index($request),
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
    public function store(StoreRequest $request, Images $images, Action $product): RedirectResponse
    {
        $product->create($images->save($request->validated()));

        return Redirect::route('products.index')->with('success', 'Product created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Action $product, string $slug): Response
    {
        return Inertia::render('Product/Show', [
            'product' => $product->show($slug),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Action $product, string $slug): Response
    {
        return Inertia::render('Product/Edit', [
            'product' => $product->edit($slug),
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
        Action $product,
        string $id,
        string $files): RedirectResponse
    {

        $data = $images->Update($request->validated(), $files);

        $product->update($id, $data);

        return Redirect::route('product.edit', $data['slug'])->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Images $images, Action $product, string $slug): RedirectResponse
    {
        $query = $product->show($slug);

        $images->Delete($query ? $query->toArray() : []);

        $product->delete($slug);

        return Redirect::route('products.index')->with('success', 'Product deleted.');
    }
}
