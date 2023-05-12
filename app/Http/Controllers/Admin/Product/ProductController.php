<?php

namespace App\Http\Controllers\Admin\Product;

use App\Actions\Product\DestroyProductAction;
use App\Actions\Product\StoreProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Dtos\Product\StoreProductData;
use App\Dtos\Product\UpdateProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
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
    public function index(Request $request): Response
    {
        return Inertia::render('Product/Index', [
            'filters' => $request->only(['search', 'category', 'availability']),
            'products_categories' => ProductCategory::getFromCache(),
            'products' => Product::query()
                -> whereSearch($request->input('search'))
                -> whereCategory($request->input('category'))
                -> whenAvailability($request->input('availability'))
                -> select(
                        'products.name',
                        'products.slug',
                        'products_categories.name as category',
                        'products.price',
                        'units.name as unit',
                        'products.stock',
                        'products.availability',
                    )
                -> join('products_categories', 'products.products_category_id', 'products_categories.id')
                -> join('units', 'products.unit', 'units.code')
                -> orderByDesc('products.id')
                -> paginate(10)
                -> withQueryString(),
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
    public function show(string $slug): Response
    {
        return Inertia::render('Product/Show', [
            'product' => Product::select(
                'products.name',
                'products.slug',
                'products_categories.name as category',
                'products.description',
                'products.price',
                'units.name as unit',
                'products.stock',
                'products.picture_1',
                'products.picture_2',
                'products.picture_3'
            )
            -> join('products_categories', 'products.products_category_id', 'products_categories.id')
            -> join('units', 'products.unit', 'units.code')
            -> whereSlug($slug)
            -> first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug): Response
    {
        return Inertia::render('Product/Edit', [
            'product' => Product::select(
                    'products.id',
                    'products.name',
                    'products.products_category_id',
                    'products.barcode',
                    'products.description',
                    'products.price',
                    'products.unit',
                    'products.stock',
                    'products.picture_1',
                    'products.picture_2',
                    'products.picture_3',
                    'products.availability'
                )
                -> whereSlug($slug)
                -> first(),
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
