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
    public function index(Request $request): Response
    {

        return Inertia::render('Product/Index', [
            'filters' => $request->only(['search', 'category', 'availability']),
            'products_categories' => $this->getProductsCategories(),
            'products' => Product::query()
                -> when($request->input('search'), function ($query, $search) {
                    $query -> where('products.name', 'like', '%'.$search.'%');
                })
                -> when($request->input('category'), function ($query, $category) {
                    $query -> where('products_categories.name', $category);
                })
                -> when($request->input('availability'), function ($query, $availability) {
                    if ($availability == 'true') {
                        $query -> where('products.availability', '1');
                    }
                    if ($availability == 'false') {
                        $query -> where('products.availability', '0');
                    }
                })
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
                -> orderBy('products.id')
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
            'products_categories' => $this->getProductsCategories(),
            'units' => $this->getUnits(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Images $images, Action $action): RedirectResponse
    {
        $action->create($images->save($request->validated()));

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
                -> where('slug', $slug)
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
                -> where('slug', $slug)
                -> first(),
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
        Action $action,
        string $id,
        string $files): RedirectResponse
    {

        $data = $images->Update($request->validated(), $files);

        $action->update($id, $data);

        return Redirect::route('product.edit', $data['slug'])->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Images $images, Action $action, string $slug): RedirectResponse
    {
        $product = $action->show($slug);

        $images->Delete($product->toArray());

        $action->delete($slug);

        return Redirect::route('products.index')->with('success', 'Product deleted.');
    }
}
