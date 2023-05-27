<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Product\Actions\DestroyProductAction;
use App\Domain\Product\Actions\StoreProductAction;
use App\Domain\Product\Actions\UpdateProductAction;
use App\Domain\Product\Dtos\StoreProductData;
use App\Domain\Product\Dtos\UpdateProductData;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\Product\StoreRequest;
use App\Http\Requests\Web\Admin\Product\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
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

    public function create(): Response
    {
        return Inertia::render('Product/Create', [
            'products_categories' => ProductCategory::getFromCache(),
            'units' => Unit::getFromCache(),
        ]);
    }

    public function store(
        StoreRequest $request,
        StoreProductAction $store_product_action): RedirectResponse
    {
        $data = StoreProductData::fromRequest($request);

        $store_product_action->handle($data);

        return Redirect::route('products.index')->with('success', 'Product created.');
    }

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

    public function destroy(
        DestroyProductAction $destroy_product_action,
        string $slug): RedirectResponse
    {

        $destroy_product_action->handle($slug);

        return Redirect::route('products.index')->with('success', 'Product deleted.');
    }
}
